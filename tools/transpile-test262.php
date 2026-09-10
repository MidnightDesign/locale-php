<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\ConstructorFixturePipeline;
use Midnight\Intl\Tools\Test262\ConstructorOptionsScriptTranslator;
use Midnight\Intl\Tools\Test262\EvidenceBuilder;
use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\FixtureResult;
use Midnight\Intl\Tools\Test262\GetterFixturePipeline;
use Midnight\Intl\Tools\Test262\IdentifierCanonicalizationPipeline;
use Midnight\Intl\Tools\Test262\InventoryAudit;

$root = dirname(__DIR__);
require $root.'/vendor/autoload.php';

function readRequiredFile(string $path, string $root): string
{
    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Unable to read '.str_replace($root.'/', '', $path).'.');
    }

    return $contents;
}

function writeRequiredFile(string $path, string $contents): void
{
    if (!is_dir(dirname($path)) && !mkdir(dirname($path), 0755, true)) {
        throw new RuntimeException('Unable to create '.dirname($path).'.');
    }
    if (file_put_contents($path, $contents) === false) {
        throw new RuntimeException('Unable to write '.$path.'.');
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
    readRequiredFile($root.'/tests/Test262/baseline.json', $root),
    true,
    flags: JSON_THROW_ON_ERROR,
);
$ecma402Revision = $baseline['active']['ecma402']['revision'];
$test262Revision = $baseline['active']['test262']['revision'];

$assertionIdentities = new AssertionIdentityExtractor();
$representations = ['associative_array', 'plain_object'];
/** @var array<string, FixturePipeline> $fixturePipelines */
$fixturePipelines = [
    'test/intl402/Locale/constructor-unicode-ext-valid.js' => new IdentifierCanonicalizationPipeline(
        $assertionIdentities,
        $test262Revision,
        $ecma402Revision,
    ),
    'test/intl402/Locale/getters-missing.js' => new GetterFixturePipeline(
        $assertionIdentities,
        $test262Revision,
        $ecma402Revision,
    ),
    'test/intl402/Locale/constructor-options-script-valid.js' => new ConstructorFixturePipeline(
        new ConstructorOptionsScriptTranslator($assertionIdentities),
        $assertionIdentities,
        $representations,
        $test262Revision,
        $ecma402Revision,
    ),
];

$fixtureSources = [];
foreach (array_keys($fixturePipelines) as $fixturePath) {
    $fixtureSources[$fixturePath] = readRequiredFile(
        $root.'/tests/Test262/upstream/'.$fixturePath,
        $root,
    );
}
$test262License = readRequiredFile($root.'/tests/Test262/upstream/LICENSE', $root);
$ecma402License = readRequiredFile($root.'/tests/Test262/upstream/ECMA-402-LICENSE.md', $root);
$initialInventorySource = readRequiredFile(
    $root.'/'.$baseline['initial']['test262']['initialInventory']['path'],
    $root,
);
if (hash('sha256', $initialInventorySource)
    !== $baseline['initial']['test262']['initialInventory']['sha256']) {
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
$corpus = json_decode(
    readRequiredFile($root.'/tests/Test262/corpus.json', $root),
    true,
    flags: JSON_THROW_ON_ERROR,
);
if ($corpus['test262Revision'] !== $test262Revision
    || $corpus['localeTree'] !== $baseline['active']['test262']['localeTree']
    || $corpus['fixtureCount'] !== $baseline['active']['test262']['fixtureCount']
    || $corpus['aggregateSha256'] !== $baseline['active']['test262']['aggregateSha256']) {
    throw new RuntimeException('The pinned Test262 corpus inventory does not match the conformance baseline.');
}

$actualHashes = [
    ...array_map(static fn (string $source): string => hash('sha256', $source), $fixtureSources),
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
    static fn (FixtureResult $result): array => $result->assertionIds(),
    $fixtureResults,
));
$inventoryAudit = InventoryAudit::run($corpus, $translatedAssertionIds);
$evidence = EvidenceBuilder::build($baseline, $corpus, $inventoryAudit, $fixtureResults);
$evidenceJson = json_encode(
    $evidence,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
)."\n";

writeRequiredFile($root.'/build/test262-results.json', $evidenceJson);

$blockingResults = array_filter(
    $fixtureResults,
    static fn (FixtureResult $result): bool => $result->blocksGeneration(),
);
if ($blockingResults !== []) {
    foreach ($blockingResults as $result) {
        $fixtureEvidence = $result->evidence();
        fwrite(
            STDERR,
            sprintf(
                "%s: %s\n",
                $fixtureEvidence['path'],
                $fixtureEvidence['reason'] ?? 'The translated fixture has execution failures.',
            ),
        );
    }
    exit(1);
}

$outputs = ['tests/Test262/evidence.json' => $evidenceJson];
foreach ($fixtureResults as $result) {
    foreach ($result->generatedFiles() as $path => $contents) {
        if (isset($outputs[$path])) {
            throw new RuntimeException('Multiple fixture pipelines generated '.$path.'.');
        }
        $outputs[$path] = $contents;
    }
}

$check = in_array('--check', $argv, true);
foreach ($outputs as $path => $contents) {
    $absolutePath = $root.'/'.$path;
    if ($check) {
        if (!is_file($absolutePath) || file_get_contents($absolutePath) !== $contents) {
            fwrite(STDERR, $path." is not reproducible.\n");
            exit(1);
        }
        continue;
    }

    writeRequiredFile($absolutePath, $contents);
}
