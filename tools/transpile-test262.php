<?php

declare(strict_types=1);

use Midnight\Intl\Tools\MagoFormatter;
use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\EvidenceBuilder;
use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\FixtureResult;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;
use Midnight\Intl\Tools\Test262\GeneratedOutputPublisher;
use Midnight\Intl\Tools\Test262\GeneratedScriptCatalog;
use Midnight\Intl\Tools\Test262\InventoryAudit;

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
$catalog = new FixtureCatalog($assertionIdentities, $representations, $test262Revision, $ecma402Revision);
/** @var Closure(FixtureCatalog): array<string, FixturePipeline> $localeStateAndOpenKeywordCatalog */
$localeStateAndOpenKeywordCatalog = require __DIR__ . '/Test262/Fixtures/locale-state-and-open-keyword.php';
/** @var Closure(FixtureCatalog): array<string, FixturePipeline> $firstDayOfWeekCatalog */
$firstDayOfWeekCatalog = require __DIR__ . '/Test262/Fixtures/first-day-of-week.php';
/** @var Closure(FixtureCatalog): array<string, FixturePipeline> $unicodeKeywordOptionsCatalog */
$unicodeKeywordOptionsCatalog = require __DIR__ . '/Test262/Fixtures/unicode-keyword-options.php';
/** @var Closure(FixtureCatalog): array<string, FixturePipeline> $coreLocaleCatalog */
$coreLocaleCatalog = require __DIR__ . '/Test262/Fixtures/core-locale.php';
/** @var Closure(FixtureCatalog): array<string, FixturePipeline> $timeZonesCatalog */
$timeZonesCatalog = require __DIR__ . '/Test262/Fixtures/time-zones.php';
/** @var Closure(FixtureCatalog): array<string, FixturePipeline> $numberingSystemsCatalog */
$numberingSystemsCatalog = require __DIR__ . '/Test262/Fixtures/numbering-systems.php';
/** @var array<string, FixturePipeline> $fixturePipelines */
$fixturePipelines = [
    ...$localeStateAndOpenKeywordCatalog($catalog),
    ...$firstDayOfWeekCatalog($catalog),
    ...$unicodeKeywordOptionsCatalog($catalog),
    ...$coreLocaleCatalog($catalog),
    ...$timeZonesCatalog($catalog),
    ...$numberingSystemsCatalog($catalog),
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
