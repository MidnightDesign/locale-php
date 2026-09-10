<?php

declare(strict_types=1);

if ($argc !== 2 || !is_dir($argv[1])) {
    fwrite(STDERR, "Usage: php tools/inventory-test262.php <pinned-test262-checkout>\n");
    exit(1);
}

$checkout = rtrim($argv[1], '/');
$root = dirname(__DIR__);
$baselineSource = file_get_contents($root.'/tests/Test262/baseline.json');
if ($baselineSource === false) {
    throw new RuntimeException('Unable to read tests/Test262/baseline.json.');
}
/** @var array{
 *     initial: array{test262: array{revision: string, initialInventory: array{sha256: string}}},
 *     active: array{test262: array{revision: string, rootTree: string, localeTree: string, fixtureCount: int, aggregateSha256: string}}
 * } $baseline */
$baseline = json_decode($baselineSource, true, flags: JSON_THROW_ON_ERROR);
$active = $baseline['active']['test262'];

$revision = $active['revision'];
$rootTree = $active['rootTree'];
$localeTree = $active['localeTree'];

$localeRoot = $checkout.'/test/intl402/Locale';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($localeRoot));
$paths = [];
foreach ($iterator as $file) {
    if (!$file instanceof SplFileInfo) {
        continue;
    }

    if ($file->isFile() && $file->getExtension() === 'js') {
        $paths[] = substr($file->getPathname(), strlen($checkout) + 1);
    }
}
sort($paths);

$fixtures = [];
$hashManifest = '';
$detectedAssertionCount = 0;

function assertionSource(string $source, int $callOffset): string
{
    $openParenthesis = strpos($source, '(', $callOffset);
    if ($openParenthesis === false) {
        throw new RuntimeException('Unable to locate an assertion argument list.');
    }

    $depth = 0;
    $quote = null;
    $escaped = false;
    $lineComment = false;
    $blockComment = false;
    $length = strlen($source);
    for ($index = $openParenthesis; $index < $length; ++$index) {
        $character = $source[$index];
        $next = $source[$index + 1] ?? '';

        if ($lineComment) {
            $lineComment = $character !== "\n";
            continue;
        }
        if ($blockComment) {
            if ($character === '*' && $next === '/') {
                $blockComment = false;
                ++$index;
            }
            continue;
        }
        if ($quote !== null) {
            if ($escaped) {
                $escaped = false;
            } elseif ($character === '\\') {
                $escaped = true;
            } elseif ($character === $quote) {
                $quote = null;
            }
            continue;
        }
        if ($character === '/' && $next === '/') {
            $lineComment = true;
            ++$index;
            continue;
        }
        if ($character === '/' && $next === '*') {
            $blockComment = true;
            ++$index;
            continue;
        }
        if (in_array($character, ["'", '"', '`'], true)) {
            $quote = $character;
            continue;
        }
        if ($character === '(') {
            ++$depth;
        } elseif ($character === ')' && --$depth === 0) {
            return substr($source, $callOffset, $index - $callOffset + 1);
        }
    }

    throw new RuntimeException('Unable to locate the end of an assertion argument list.');
}

foreach ($paths as $path) {
    $source = file_get_contents($checkout.'/'.$path);
    if ($source === false) {
        throw new RuntimeException('Unable to read '.$path);
    }

    $sha256 = hash('sha256', $source);
    $hashManifest .= strtoupper($sha256).'  '.$path."\n";
    $assertions = [];
    preg_match_all(
        '/\b(assert(?:\.[A-Za-z]+)?|verifyProperty|verifyEqualTo)\s*\(/',
        $source,
        $calls,
        PREG_OFFSET_CAPTURE,
    );
    foreach ($calls[1] as [$call, $offset]) {
        $line = substr_count(substr($source, 0, $offset), "\n") + 1;
        $lineStart = strrpos(substr($source, 0, $offset), "\n");
        $column = $offset - ($lineStart === false ? -1 : $lineStart);
        $assertions[] = [
            'id' => sprintf('%s:L%d:C%d:%s', $path, $line, $column, $call),
            'line' => $line,
            'column' => $column,
            'call' => $call,
            'sha256' => hash('sha256', assertionSource($source, $offset)),
            'status' => in_array($path, [
                'test/intl402/Locale/getters-missing.js',
                'test/intl402/Locale/constructor-options-script-valid.js',
            ], true) ? 'see-translated-evidence' : 'translation_gap',
        ];
        ++$detectedAssertionCount;
    }

    $partiallyTranslated = $path === 'test/intl402/Locale/getters-missing.js';
    $translated = $path === 'test/intl402/Locale/constructor-options-script-valid.js';
    $fixture = [
        'path' => $path,
        'sha256' => $sha256,
        'status' => match (true) {
            $translated => 'translated',
            $partiallyTranslated => 'partially_translated',
            default => 'translation_gap',
        },
        'reason' => match (true) {
            $translated => 'All source assertions and representation executions are detailed in tests/Test262/evidence.json.',
            $partiallyTranslated => 'Applicable assertions and out-of-slice gaps are detailed in tests/Test262/evidence.json.',
            default => 'The fixture remains visible as unfinished work for the incomplete initial slice.',
        },
        'detectedAssertions' => $assertions,
    ];
    if (!$translated && !$partiallyTranslated) {
        $fixture['unresolvedAssertionScope'] = [
            'id' => $path.':unresolved-assertion-scope',
            'status' => 'translation_gap',
            'reason' => 'Parameterized executions and custom helper behavior remain part of this fixture-level translation gap until translated evidence accounts for them.',
        ];
    }
    $fixtures[] = $fixture;
}

$aggregateSha256 = hash('sha256', rtrim($hashManifest, "\n"));
if (count($fixtures) !== $active['fixtureCount'] || $aggregateSha256 !== $active['aggregateSha256']) {
    throw new RuntimeException('The Test262 fixture inventory does not match the active tracked input.');
}

$currentFixtures = [];
foreach ($fixtures as $fixture) {
    $currentFixtures[$fixture['path']] = $fixture;
}
$initialInventoryPath = $root.'/tests/Test262/initial-inventory.json';
$initialInventorySource = is_file($initialInventoryPath)
    ? file_get_contents($initialInventoryPath)
    : false;
$createdInitialInventory = false;
if ($initialInventorySource === false) {
    if ($revision !== $baseline['initial']['test262']['revision']) {
        throw new RuntimeException('The immutable initial Test262 inventory is missing.');
    }
    $initialFixtureHashes = [];
    $initialAssertionHashes = [];
    foreach ($fixtures as $fixture) {
        $initialFixtureHashes[$fixture['path']] = $fixture['sha256'];
        foreach ($fixture['detectedAssertions'] as $assertion) {
            $initialAssertionHashes[$assertion['id']] = $assertion['sha256'];
        }
    }
    $initialInventorySource = json_encode(
        [
            'test262Revision' => $revision,
            'fixtures' => $initialFixtureHashes,
            'assertions' => $initialAssertionHashes,
        ],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
    )."\n";
    file_put_contents($initialInventoryPath, $initialInventorySource);
    $createdInitialInventory = true;
}
if (!$createdInitialInventory && hash('sha256', $initialInventorySource)
    !== $baseline['initial']['test262']['initialInventory']['sha256']) {
    throw new RuntimeException('The immutable initial Test262 inventory failed its integrity check.');
}
/** @var array{test262Revision: string, fixtures: array<string, string>, assertions: array<string, string>} $initialInventory */
$initialInventory = json_decode($initialInventorySource, true, flags: JSON_THROW_ON_ERROR);
$previousFixtures = [];
foreach ($initialInventory['fixtures'] as $path => $sha256) {
    $previousFixtures[$path] = ['sha256' => $sha256];
}
$addedFixtures = array_values(array_diff(array_keys($currentFixtures), array_keys($previousFixtures)));
$removedFixtures = array_values(array_diff(array_keys($previousFixtures), array_keys($currentFixtures)));
$changedFixtures = [];
foreach (array_intersect(array_keys($currentFixtures), array_keys($previousFixtures)) as $path) {
    if ($currentFixtures[$path]['sha256'] !== $previousFixtures[$path]['sha256']) {
        $changedFixtures[] = $path;
    }
}

/**
 * @param array<string, array{detectedAssertions: list<array<string, mixed>>}> $fixturesByPath
 *
 * @return array<string, array<string, mixed>>
 */
function assertionsById(array $fixturesByPath): array
{
    $assertions = [];
    foreach ($fixturesByPath as $fixture) {
        foreach ($fixture['detectedAssertions'] as $assertion) {
            if (is_string($assertion['id'] ?? null)) {
                $assertions[$assertion['id']] = $assertion;
            }
        }
    }

    return $assertions;
}

$currentAssertions = assertionsById($currentFixtures);
$addedAssertions = array_values(array_diff(array_keys($currentAssertions), array_keys($initialInventory['assertions'])));
$removedAssertions = array_values(array_diff(array_keys($initialInventory['assertions']), array_keys($currentAssertions)));
$changedAssertions = [];
foreach (array_intersect(array_keys($currentAssertions), array_keys($initialInventory['assertions'])) as $id) {
    if (($currentAssertions[$id]['sha256'] ?? null) !== $initialInventory['assertions'][$id]) {
        $changedAssertions[] = $id;
    }
}

$inventory = [
    'test262Revision' => $revision,
    'test262RootTree' => $rootTree,
    'localeTree' => $localeTree,
    'fixtureCount' => count($fixtures),
    'detectedAssertionCount' => $detectedAssertionCount,
    'aggregateSha256' => $aggregateSha256,
    'assertionDetection' => 'Source identities for every standard Test262 assert, assert.*, verifyProperty, and verifyEqualTo call. Fixture-level translation_gap covers parameterized executions and custom helper behavior until translated evidence accounts for them.',
    'comparison' => [
        'fromRevision' => $initialInventory['test262Revision'],
        'toRevision' => $revision,
        'addedFixtures' => $addedFixtures,
        'changedFixtures' => $changedFixtures,
        'removedFixtures' => $removedFixtures,
        'addedAssertions' => $addedAssertions,
        'changedAssertions' => $changedAssertions,
        'removedAssertions' => $removedAssertions,
    ],
    'fixtures' => $fixtures,
];

file_put_contents(
    $root.'/tests/Test262/corpus.json',
    json_encode($inventory, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n",
);
