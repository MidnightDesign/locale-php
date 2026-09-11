<?php

declare(strict_types=1);

use Midnight\Intl\Tools\MagoFormatter;
use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\ConstructorFixturePipeline;
use Midnight\Intl\Tools\Test262\ConstructorOptionsScriptTranslator;
use Midnight\Intl\Tools\Test262\EvidenceBuilder;
use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\FixtureResult;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalogContext;
use Midnight\Intl\Tools\Test262\GeneratedOutputPublisher;
use Midnight\Intl\Tools\Test262\GeneratedScriptCatalog;
use Midnight\Intl\Tools\Test262\GetterFixturePipeline;
use Midnight\Intl\Tools\Test262\GrandfatheredLikelySubtagsPipeline;
use Midnight\Intl\Tools\Test262\IdentifierCanonicalizationPipeline;
use Midnight\Intl\Tools\Test262\IdentifierRejectionPipeline;
use Midnight\Intl\Tools\Test262\InventoryAudit;
use Midnight\Intl\Tools\Test262\LikelySubtagsPipeline;
use Midnight\Intl\Tools\Test262\LocaleMethodFixturePipeline;
use Midnight\Intl\Tools\Test262\LocaleObjectPipeline;
use Midnight\Intl\Tools\Test262\MappedConstructorOptionPipeline;
use Midnight\Intl\Tools\Test262\MappedLocaleStatePipeline;
use Midnight\Intl\Tools\Test262\OptionObservationPipeline;
use Midnight\Intl\Tools\Test262\RemoveLikelySubtagsPipeline;
use Midnight\Intl\Tools\Test262\SourceBoundFixturePipeline;
use Midnight\Intl\Tools\Test262\UndefinedConstructorOptionPipeline;

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

function readRequiredFile(string $path, string $root): string
{
    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Unable to read ' . str_replace($root . '/', '', $path) . '.');
    }

    return $contents;
}

function writeRequiredFile(string $path, string $contents): void
{
    if (!is_dir(dirname($path)) && !mkdir(dirname($path), 0755, true)) {
        throw new RuntimeException('Unable to create ' . dirname($path) . '.');
    }
    if (file_put_contents($path, $contents) === false) {
        throw new RuntimeException('Unable to write ' . $path . '.');
    }
}

/** @var array{
 *     initial: array{
 *         ecma402: array{
 *             revision: string,
 *             localeSource: array{sha256: string}
 *         },
 *         test262: array{
 *             initialInventory: array{path: string, sha256: string}
 *         }&array<string, mixed>
 *     },
 *     active: array{
 *         ecma402: array{
 *             revision: string,
 *             localeSource: array{sha256: string}
 *         }&array<string, mixed>,
 *         test262: array{
 *             revision: string,
 *             localeTree: string,
 *             fixtureCount: int,
 *             aggregateSha256: string,
 *             sourceCopies: array<string, string>
 *         }&array<string, mixed>
 *     },
 *     trackingPolicy: string
 * } $baseline */
$baseline = json_decode(
    readRequiredFile($root . '/tests/Test262/baseline.json', $root),
    true,
    flags: JSON_THROW_ON_ERROR,
);
$ecma402Revision = $baseline['active']['ecma402']['revision'];
$test262Revision = $baseline['active']['test262']['revision'];

$assertionIdentities = new AssertionIdentityExtractor();
$representations = ['associative_array', 'plain_object'];
/** @param list<string> $phpRepresentations */
$sourceBoundPipeline = static function (
    FixturePipeline $pipeline,
    array $phpRepresentations,
    string $sourceSha256,
) use ($assertionIdentities): SourceBoundFixturePipeline {
    /** @var list<string> $phpRepresentations */
    return new SourceBoundFixturePipeline($pipeline, $assertionIdentities, $phpRepresentations, $sourceSha256);
};
/**
 * @param list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string|bool, property?: string}> $cases
 */
$mappedOptionPipeline = static function (
    string $optionName,
    string $sourceSha256,
    string $className,
    array $cases,
) use (
    $assertionIdentities,
    $representations,
    $test262Revision,
    $ecma402Revision,
    $sourceBoundPipeline,
): SourceBoundFixturePipeline {
    /** @var list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string|bool, property?: string}> $cases */
    return $sourceBoundPipeline(
        new MappedConstructorOptionPipeline(
            $assertionIdentities,
            $representations,
            $test262Revision,
            $ecma402Revision,
            $optionName,
            $cases,
        ),
        $representations,
        $sourceSha256,
    );
};
/**
 * @param list<array<string, mixed>> $values
 * @return list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string}>
 */
$invalidCases = static fn(array $values): array => array_map(static function (mixed $value): array {
    assert(is_array($value));

    return [
        'assertion' => 0,
        'tag' => 'en',
        'value' => $value,
        'expected' => Midnight\Intl\Exception\RangeError::class,
    ];
}, $values);

/** @return array{type: 'string', value: string} */
$stringValue = static fn(string $value): array => ['type' => 'string', 'value' => $value];

/**
 * @param list<array{array<string, mixed>, string}> $values
 * @param list<array{int, string, ?string}>         $observations
 * @return list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string|bool, property?: string}>
 */
$optionCases = static function (array $values, array $observations): array {
    $cases = [];
    foreach ($values as [$value, $expected]) {
        foreach ($observations as [$assertion, $tag, $property]) {
            $case = [
                'assertion' => $assertion,
                'tag' => $tag,
                'value' => $value,
                'expected' => $expected,
            ];
            if ($property !== null) {
                $case['property'] = $property;
            }
            $cases[] = $case;
        }
    }

    return $cases;
};

$openKeywordValues = array_map(static fn(array $row): array => [$stringValue($row[0]), $row[1]], [
    ['abc',             'en-u-%s-abc'],
    ['abcd',            'en-u-%s-abcd'],
    ['abcde',           'en-u-%s-abcde'],
    ['abcdef',          'en-u-%s-abcdef'],
    ['abcdefg',         'en-u-%s-abcdefg'],
    ['abcdefgh',        'en-u-%s-abcdefgh'],
    ['12345678',        'en-u-%s-12345678'],
    ['1234abcd',        'en-u-%s-1234abcd'],
    ['1234abcd-abc123', 'en-u-%s-1234abcd-abc123'],
]);

/**
 * @param non-empty-string $key
 * @return list<array{array<string, mixed>, string}>
 */
$forKeyword = static fn(string $key): array => array_map(static fn(array $row): array => [
    $row[0],
    sprintf($row[1], $key),
], $openKeywordValues);

/**
 * @param list<array{string, string|bool|null}> $values
 * @return list<array{assertion: int, property: string, expected: string|bool|null}>
 */
$stateExpectations = static function (int $firstAssertion, array $values): array {
    $expectations = [];
    foreach ($values as $offset => $value) {
        /** @var array{string, string|bool|null} $value */
        $expectations[] = [
            'assertion' => $firstAssertion + $offset,
            'property' => $value[0],
            'expected' => $value[1],
        ];
    }

    return $expectations;
};

/** @param list<array{tag: string, options?: array<string, mixed>, expectations: list<array{assertion: int, property: string, expected: string|bool|null}>}> $scenarios */
$mappedStatePipeline = static function (string $sourceSha256, array $scenarios) use (
    $assertionIdentities,
    $test262Revision,
    $ecma402Revision,
    $sourceBoundPipeline,
): SourceBoundFixturePipeline {
    /** @var list<array{tag: string, options?: array<string, mixed>, expectations: list<array{assertion: int, property: string, expected: string|bool|null}>}> $scenarios */
    $pipeline = new MappedLocaleStatePipeline($assertionIdentities, $test262Revision, $ecma402Revision, $scenarios);

    return $sourceBoundPipeline($pipeline, $pipeline->phpRepresentations(), $sourceSha256);
};
$catalogContext = new FixtureCatalogContext(
    $mappedStatePipeline,
    $stateExpectations,
    $mappedOptionPipeline,
    $invalidCases,
    $stringValue,
    $optionCases,
    $forKeyword,
    $assertionIdentities,
    $representations,
    $test262Revision,
    $ecma402Revision,
    $sourceBoundPipeline,
);
/** @var Closure(FixtureCatalogContext): array<string, FixturePipeline> $localeStateAndOpenKeywordCatalog */
$localeStateAndOpenKeywordCatalog = require __DIR__ . '/Test262/Fixtures/locale-state-and-open-keyword.php';
/** @var Closure(FixtureCatalogContext): array<string, FixturePipeline> $firstDayOfWeekCatalog */
$firstDayOfWeekCatalog = require __DIR__ . '/Test262/Fixtures/first-day-of-week.php';
/** @var Closure(FixtureCatalogContext): array<string, FixturePipeline> $unicodeKeywordOptionsCatalog */
$unicodeKeywordOptionsCatalog = require __DIR__ . '/Test262/Fixtures/unicode-keyword-options.php';
/** @var Closure(FixtureCatalogContext): array<string, FixturePipeline> $coreLocaleCatalog */
$coreLocaleCatalog = require __DIR__ . '/Test262/Fixtures/core-locale.php';
/** @var array<string, FixturePipeline> $fixturePipelines */
$fixturePipelines = [
    ...$localeStateAndOpenKeywordCatalog($catalogContext),
    ...$firstDayOfWeekCatalog($catalogContext),
    ...$unicodeKeywordOptionsCatalog($catalogContext),
    ...$coreLocaleCatalog($catalogContext),
];

$fixtureSources = [];
foreach (array_keys($fixturePipelines) as $fixturePath) {
    $fixtureSources[$fixturePath] = readRequiredFile($root . '/tests/Test262/upstream/' . $fixturePath, $root);
}
$test262License = readRequiredFile($root . '/tests/Test262/upstream/LICENSE', $root);
$ecma402License = readRequiredFile($root . '/tests/Test262/upstream/ECMA-402-LICENSE.md', $root);
$initialInventorySource = readRequiredFile(
    $root . '/' . $baseline['initial']['test262']['initialInventory']['path'],
    $root,
);
if (hash('sha256', $initialInventorySource) !== $baseline['initial']['test262']['initialInventory']['sha256']) {
    throw new RuntimeException('The immutable initial Test262 inventory failed its integrity check.');
}

/** @var array{
 *     test262Revision: string,
 *     localeTree: string,
 *     fixtureCount: int,
 *     aggregateSha256: string,
 *     comparison: array<string, mixed>,
 *     fixtures: list<array{status: string, detectedAssertions: list<array<string, mixed>>}>
 * } $corpus */
$corpus = json_decode(readRequiredFile($root . '/tests/Test262/corpus.json', $root), true, flags: JSON_THROW_ON_ERROR);
if (
    $corpus['test262Revision'] !== $test262Revision
    || $corpus['localeTree'] !== $baseline['active']['test262']['localeTree']
    || $corpus['fixtureCount'] !== $baseline['active']['test262']['fixtureCount']
    || $corpus['aggregateSha256'] !== $baseline['active']['test262']['aggregateSha256']
) {
    throw new RuntimeException('The pinned Test262 corpus inventory does not match the conformance baseline.');
}

$actualHashes = [
    ...array_map(static fn(string $source): string => hash('sha256', $source), $fixtureSources),
    'LICENSE' => hash('sha256', $test262License),
    'ECMA-402-LICENSE.md' => hash('sha256', $ecma402License),
];
foreach ($baseline['active']['test262']['sourceCopies'] as $path => $expectedHash) {
    if (!isset($actualHashes[$path]) || $actualHashes[$path] !== $expectedHash) {
        throw new RuntimeException(sprintf('Pinned upstream source integrity failed for %s.', $path));
    }
}

$fixtureResults = [];
foreach ($fixturePipelines as $fixturePath => $pipeline) {
    $fixtureResults[] = $pipeline->run($fixtureSources[$fixturePath], $fixturePath);
}
$translatedAssertionIds = array_merge(...array_map(
    static fn(FixtureResult $result): array => $result->assertionIds(),
    $fixtureResults,
));
$inventoryAudit = InventoryAudit::run($corpus, $translatedAssertionIds);
$evidence = EvidenceBuilder::build($baseline, $corpus, $inventoryAudit, $fixtureResults);
$evidenceJson = json_encode($evidence, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";

writeRequiredFile($root . '/build/test262-results.json', $evidenceJson);

$blockingResults = array_filter($fixtureResults, static fn(FixtureResult $result): bool => $result->blocksGeneration());
if ($blockingResults !== []) {
    foreach ($blockingResults as $result) {
        $fixtureEvidence = $result->evidence();
        fwrite(STDERR, sprintf(
            "%s: %s\n",
            $fixtureEvidence['path'],
            $fixtureEvidence['reason'] ?? 'The translated fixture has execution failures.',
        ));
    }
    exit(1);
}

$generatedFiles = [];
foreach ($fixtureResults as $result) {
    foreach ($result->generatedScripts() as $script) {
        $path = $script->path();
        if ($path === 'tests/Test262/evidence.json' || isset($generatedFiles[$path])) {
            throw new RuntimeException('Multiple fixture pipelines generated ' . $path . '.');
        }
        $generatedFiles[$path] = $script->contents();
    }
}
$outputs = [
    'tests/Test262/evidence.json' => $evidenceJson,
    ...MagoFormatter::formatAll($root, $generatedFiles),
];

$check = in_array('--check', $argv, true);
$expectedGeneratedFiles = array_values(array_filter(
    array_keys($outputs),
    static fn(string $path): bool => str_starts_with($path, 'tests/Test262/Generated/'),
));
sort($expectedGeneratedFiles);
$staleGeneratedFiles = array_values(array_diff(
    GeneratedScriptCatalog::generatedPhpFiles($root),
    $expectedGeneratedFiles,
));
if ($check) {
    if ($staleGeneratedFiles !== []) {
        foreach ($staleGeneratedFiles as $path) {
            fwrite(STDERR, $path . " is stale.\n");
        }
        exit(1);
    }
    foreach ($outputs as $path => $contents) {
        $absolutePath = $root . '/' . $path;
        if (!is_file($absolutePath) || file_get_contents($absolutePath) !== $contents) {
            fwrite(STDERR, $path . " is not reproducible.\n");
            exit(1);
        }
    }

    exit(0);
}

(new GeneratedOutputPublisher($root))->publish($outputs);
