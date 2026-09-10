<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\ConstructorFixturePipeline;
use Midnight\Intl\Tools\Test262\ConstructorOptionsScriptTranslator;
use Midnight\Intl\Tools\Test262\InventoryAudit;

$root = dirname(__DIR__);
require $root.'/vendor/autoload.php';
require $root.'/tools/Test262/ConstructorFixturePipeline.php';
require $root.'/tools/Test262/ConstructorOptionsScriptTranslator.php';
require $root.'/tools/Test262/InventoryAudit.php';

$baselineSource = readRequiredFile($root.'/tests/Test262/baseline.json');
/** @var array{
 *     initial: array{
 *         ecma402: array{
 *             revision: string,
 *             rootTree: string,
 *             localeSource: array{path: string, gitBlob: string, sha256: string},
 *             license: array<string, mixed>
 *         },
 *         test262: array{initialInventory: array{path: string, sha256: string}}&array<string, mixed>
 *     },
 *     active: array{
 *         ecma402: array{
 *             revision: string,
 *             rootTree: string,
 *             localeSource: array{path: string, gitBlob: string, sha256: string}
 *         },
 *         test262: array{
 *             revision: string,
 *             rootTree: string,
 *             localeTree: string,
 *             fixtureCount: int,
 *             aggregateSha256: string,
 *             sourceCopies: array<string, string>
 *         }
 *     },
 *     trackingPolicy: string
 * } $baselineConfig */
$baselineConfig = json_decode($baselineSource, true, flags: JSON_THROW_ON_ERROR);
$ecma402Revision = $baselineConfig['active']['ecma402']['revision'];
$test262Revision = $baselineConfig['active']['test262']['revision'];
$getterFixturePath = 'test/intl402/Locale/getters-missing.js';
$constructorFixturePath = 'test/intl402/Locale/constructor-options-script-valid.js';
$representations = ['associative_array', 'plain_object'];

function readRequiredFile(string $path): string
{
    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Unable to read '.str_replace(dirname(__DIR__).'/', '', $path).'.');
    }

    return $contents;
}

/**
 * @return array{
 *     generated: string,
 *     evidence: array{
 *         path: string,
 *         sha256: string,
 *         status: string,
 *         assertions: list<array{id: string, status: string, phpRepresentations?: list<string>, reason?: string}>
 *     }
 * }
 */
function translateGetterFixture(
    string $source,
    string $fixturePath,
    string $test262Revision,
    string $ecma402Revision,
): array
{
    preg_match_all(
        '/\bassert\.sameValue\s*\(/',
        $source,
        $sourceAssertions,
        PREG_OFFSET_CAPTURE,
    );
    $sourceAssertionIds = [];
    foreach ($sourceAssertions[0] as $match) {
        $line = substr_count(substr($source, 0, $match[1]), "\n") + 1;
        $lineStart = strrpos(substr($source, 0, $match[1]), "\n");
        $column = $match[1] - ($lineStart === false ? -1 : $lineStart);
        $sourceAssertionIds[] = sprintf(
            '%s:L%d:C%d:assert.sameValue',
            $fixturePath,
            $line,
            $column,
        );
    }

    preg_match_all(
        '/var loc = new Intl\.Locale\("(?<tag>[^"]+)"\);(?<body>.*?)(?=\nvar loc =|\z)/s',
        $source,
        $cases,
        PREG_SET_ORDER,
    );

    $rows = [];
    $inventory = [];
    $assertionNumber = 0;
    foreach ($cases as $case) {
        preg_match_all(
            '/assert\.sameValue\(loc\.(?<property>baseName|language|script|region|variants),\s*(?<expected>undefined|"[^"]*"|\'[^\']*\')\);/',
            $case['body'],
            $assertions,
            PREG_SET_ORDER,
        );

        $expected = [];
        $caseAssertionIndexes = [];
        foreach ($assertions as $assertion) {
            ++$assertionNumber;
            $caseAssertionIndexes[] = $assertionNumber;
            $property = $assertion['property'];
            $value = $assertion['expected'] === 'undefined'
                ? null
                : substr($assertion['expected'], 1, -1);
            $id = $sourceAssertionIds[$assertionNumber - 1] ?? throw new RuntimeException(
                'Translation gap: a getter assertion has no source identity.',
            );

            if ($property === 'variants') {
                $inventory[] = [
                    'id' => $id,
                    'status' => 'translation_gap',
                    'reason' => 'The variants property is outside the initial language/script/region slice.',
                ];
                continue;
            }

            $expected[$property] = $value;
            $inventory[] = [
                'id' => $id,
                'status' => 'applicable',
                'phpRepresentations' => ['direct'],
            ];
        }

        if (!str_contains($case['tag'], '-1901')) {
            $rows[$case['tag']] = $expected;
            continue;
        }

        foreach ($caseAssertionIndexes as $index) {
            $inventory[$index - 1] = [
                'id' => $sourceAssertionIds[$index - 1],
                'status' => 'translation_gap',
                'reason' => 'Variants in the input identifier are outside the initial slice.',
            ];
        }
    }

    if ($assertionNumber !== count($sourceAssertionIds)) {
        throw new RuntimeException('Translation gap: a getter source assertion was not translated or classified.');
    }

    $export = preg_replace('/[ \t]+$/m', '', var_export($rows, true));
    if ($export === null) {
        throw new RuntimeException('Unable to format the generated getter cases.');
    }
    $generated = <<<PHP
<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$test262Revision}; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 {$ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GettersMissingTest extends TestCase
{
    /** @return list<array{string, array<string, string|null>}> */
    public static function cases(): array
    {
        return array_map(
            static fn (array \$expected, string \$tag): array => [\$tag, \$expected],
            {$export},
            array_keys({$export}),
        );
    }

    /** @param array<string, string|null> \$expected */
    #[DataProvider('cases')]
    public function testTranslatedGetterAssertions(string \$tag, array \$expected): void
    {
        \$locale = new Locale(\$tag);

        foreach (\$expected as \$property => \$value) {
            self::assertSame(\$value, \$locale->{\$property});
        }
    }
}
PHP;

    return [
        'generated' => $generated,
        'evidence' => [
            'path' => $fixturePath,
            'sha256' => hash('sha256', $source),
            'status' => 'partially_translated',
            'assertions' => array_values($inventory),
        ],
    ];
}

$getterSource = readRequiredFile($root.'/tests/Test262/upstream/'.$getterFixturePath);
$constructorSource = readRequiredFile($root.'/tests/Test262/upstream/'.$constructorFixturePath);
$test262License = readRequiredFile($root.'/tests/Test262/upstream/LICENSE');
$ecma402License = readRequiredFile($root.'/tests/Test262/upstream/ECMA-402-LICENSE.md');
$corpusSource = readRequiredFile($root.'/tests/Test262/corpus.json');
$initialInventorySource = readRequiredFile($root.'/'.$baselineConfig['initial']['test262']['initialInventory']['path']);
if (hash('sha256', $initialInventorySource)
    !== $baselineConfig['initial']['test262']['initialInventory']['sha256']) {
    throw new RuntimeException('The immutable initial Test262 inventory failed its integrity check.');
}

/** @var array{
 *     test262Revision: string,
 *     test262RootTree: string,
 *     localeTree: string,
 *     fixtureCount: int,
 *     detectedAssertionCount: int,
 *     aggregateSha256: string,
 *     comparison: array<string, mixed>,
 *     fixtures: list<array{
 *         path: string,
 *         status: string,
 *         reason: string,
 *         detectedAssertions: list<array<string, mixed>>,
 *         unresolvedAssertionScope?: array<string, mixed>
 *     }>
 * } $corpus */
$corpus = json_decode($corpusSource, true, flags: JSON_THROW_ON_ERROR);
if ($corpus['test262Revision'] !== $test262Revision
    || $corpus['localeTree'] !== $baselineConfig['active']['test262']['localeTree']
    || $corpus['fixtureCount'] !== $baselineConfig['active']['test262']['fixtureCount']
    || $corpus['aggregateSha256'] !== $baselineConfig['active']['test262']['aggregateSha256']) {
    throw new RuntimeException('The pinned Test262 corpus inventory does not match the conformance baseline.');
}

$expectedHashes = $baselineConfig['active']['test262']['sourceCopies'];
$actualHashes = [
    $getterFixturePath => hash('sha256', $getterSource),
    $constructorFixturePath => hash('sha256', $constructorSource),
    'LICENSE' => hash('sha256', $test262License),
    'ECMA-402-LICENSE.md' => hash('sha256', $ecma402License),
];
foreach ($expectedHashes as $path => $expectedHash) {
    if ($actualHashes[$path] !== $expectedHash) {
        throw new RuntimeException(sprintf('Pinned upstream source integrity failed for %s.', $path));
    }
}

$constructorResult = (new ConstructorFixturePipeline(
    new ConstructorOptionsScriptTranslator(),
))->run(
    $constructorSource,
    $constructorFixturePath,
    $representations,
);
if ($constructorResult['status'] === 'translation_gap') {
    $translationGapEvidence = json_encode(
        [
            'baseline' => ['ecma402' => $ecma402Revision, 'test262' => $test262Revision],
            'fixtures' => [[
                'path' => $constructorFixturePath,
                'status' => 'translation_gap',
                'reason' => $constructorResult['reason'],
            ]],
        ],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
    )."\n";
    $resultDirectory = $root.'/build';
    if (!is_dir($resultDirectory) && !mkdir($resultDirectory, 0755, true)) {
        throw new RuntimeException('Unable to create the Test262 result directory.');
    }
    file_put_contents($resultDirectory.'/test262-results.json', $translationGapEvidence);
    fwrite(STDERR, $constructorResult['reason']."\n");
    exit(1);
}
$constructorPassing = $constructorResult['status'] === 'passing';
$caseExport = preg_replace(
    '/[ \t]+$/m',
    '',
    var_export($constructorResult['generatedCases'], true),
);
if ($caseExport === null) {
    throw new RuntimeException('Unable to format the generated constructor cases.');
}
$constructorGenerated = <<<PHP
<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$constructorFixturePath} at Test262 {$test262Revision}.
// Spec baseline: ECMA-402 {$ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsScriptValidTest extends TestCase
{
    /**
     * @return array<string, array{
     *     string,
     *     string,
     *     array{type: 'null'}|array{type: 'string'|'stringable', value: string},
     *     string,
     *     string
     * }>
     */
    public static function cases(): array
    {
        return {$caseExport};
    }

    /** @param array{type: 'null'}|array{type: 'string'|'stringable', value: string} \$optionValue */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(
        string \$assertionId,
        string \$tag,
        array \$optionValue,
        string \$representation,
        string \$expected,
    ): void {
        \$result = ConstructorOptionAssertion::evaluate(
            \$tag,
            'script',
            \$optionValue,
            \$representation,
            \$expected,
        );

        self::assertSame(
            'passing',
            \$result['status'],
            \$assertionId.': '.(\$result['failure'] ?? 'unknown failure'),
        );
    }
}
PHP;

$getter = translateGetterFixture(
    $getterSource,
    $getterFixturePath,
    $test262Revision,
    $ecma402Revision,
);
$translatedAssertionIds = [];
foreach ($constructorResult['assertions'] as $assertion) {
    if (is_string($assertion['id'] ?? null)) {
        $translatedAssertionIds[] = $assertion['id'];
    }
}
foreach ($getter['evidence']['assertions'] as $assertion) {
    $translatedAssertionIds[] = $assertion['id'];
}
$inventoryAudit = InventoryAudit::run($corpus, $translatedAssertionIds);
$inventoryComplete = $inventoryAudit['complete'];
$translationGaps = 0;
foreach ($corpus['fixtures'] as $fixture) {
    if ($fixture['status'] === 'translation_gap') {
        $translationGaps += count($fixture['detectedAssertions']) + 1;
    }
}
foreach ($getter['evidence']['assertions'] as $assertion) {
    if ($assertion['status'] === 'translation_gap') {
        ++$translationGaps;
    }
}

$constructorEvidence = [
    'path' => $constructorFixturePath,
    'sha256' => hash('sha256', $constructorSource),
    ...array_diff_key($constructorResult, ['generatedCases' => true]),
];
function isConformanceEligible(
    bool $inventoryComplete,
    int $translationGaps,
    bool $constructorPassing,
    string $getterStatus,
): bool {
    return $inventoryComplete
        && $translationGaps === 0
        && $constructorPassing
        && $getterStatus === 'passing';
}

$conformanceEligible = isConformanceEligible(
    $inventoryComplete,
    $translationGaps,
    $constructorPassing,
    $getter['evidence']['status'],
);
$evidence = [
    'baseline' => $baselineConfig['initial'],
    'trackedInputs' => [
        'ecma402' => [
            ...$baselineConfig['active']['ecma402'],
            'comparisonAgainstInitial' => [
                'fromRevision' => $baselineConfig['initial']['ecma402']['revision'],
                'toRevision' => $baselineConfig['active']['ecma402']['revision'],
                'localeSourceChanged' => $baselineConfig['initial']['ecma402']['localeSource']['sha256']
                    !== $baselineConfig['active']['ecma402']['localeSource']['sha256'],
            ],
        ],
        'test262' => [
            ...$baselineConfig['active']['test262'],
            'comparison' => $corpus['comparison'],
        ],
        'policy' => $baselineConfig['trackingPolicy'],
    ],
    'inventory' => [
        'path' => 'tests/Test262/corpus.json',
        'fixtureCount' => $corpus['fixtureCount'],
        'aggregateSha256' => $corpus['aggregateSha256'],
        'complete' => $inventoryComplete,
        'auditFailures' => $inventoryAudit['reasons'],
    ],
    'summary' => [
        'translatedFixtures' => 2,
        'passingFixtures' => $constructorPassing ? 1 : 0,
        'partiallyTranslatedFixtures' => 1,
        'translationGaps' => $translationGaps,
        'executionFailures' => $constructorResult['executionFailures'],
    ],
    'conformanceClaim' => [
        'eligible' => $conformanceEligible,
        'reason' => $conformanceEligible
            ? 'Every inventoried applicable assertion and required representation passes.'
            : 'The initial slice has an incomplete inventory, translation gaps, incomplete applicable translations, or execution failures.',
    ],
    'fixtures' => [$constructorEvidence, $getter['evidence']],
];
$evidenceJson = json_encode(
    $evidence,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
)."\n";

$outputs = [
    $root.'/tests/Test262/Generated/GettersMissingTest.php' => $getter['generated'],
    $root.'/tests/Test262/Generated/ConstructorOptionsScriptValidTest.php' => $constructorGenerated,
    $root.'/tests/Test262/evidence.json' => $evidenceJson,
];
$resultPath = $root.'/build/test262-results.json';
if (!is_dir(dirname($resultPath)) && !mkdir(dirname($resultPath), 0755, true)) {
    throw new RuntimeException('Unable to create the Test262 result directory.');
}
file_put_contents($resultPath, $evidenceJson);

$check = in_array('--check', $argv, true);
foreach ($outputs as $path => $contents) {
    if ($check) {
        if (!is_file($path) || file_get_contents($path) !== $contents) {
            fwrite(STDERR, str_replace($root.'/', '', $path)." is not reproducible.\n");
            exit(1);
        }
        continue;
    }

    if (!is_dir(dirname($path)) && !mkdir(dirname($path), 0755, true)) {
        throw new RuntimeException('Unable to create '.dirname($path).'.');
    }
    file_put_contents($path, $contents);
}

if (!$constructorPassing) {
    fwrite(STDERR, "The translated Test262 constructor fixture has execution failures.\n");
    exit(1);
}
