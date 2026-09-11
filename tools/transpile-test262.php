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
use Midnight\Intl\Tools\Test262\IdentifierRejectionPipeline;
use Midnight\Intl\Tools\Test262\InventoryAudit;
use Midnight\Intl\Tools\Test262\LocaleObjectPipeline;
use Midnight\Intl\Tools\Test262\MappedConstructorOptionPipeline;
use Midnight\Intl\Tools\Test262\OptionObservationPipeline;
use Midnight\Intl\Tools\Test262\UndefinedConstructorOptionPipeline;

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
/**
 * @param list<array{assertion: int, tag: string, value: array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}, expected: string}> $cases
 */
$mappedOptionPipeline = static function (
    string $optionName,
    string $fixtureName,
    string $className,
    array $cases,
) use ($assertionIdentities, $representations, $test262Revision, $ecma402Revision): MappedConstructorOptionPipeline {
    /** @var list<array{assertion: int, tag: string, value: array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}, expected: string}> $cases */
    return new MappedConstructorOptionPipeline(
        $assertionIdentities,
        $representations,
        $test262Revision,
        $ecma402Revision,
        $optionName,
        'tests/Test262/Generated/'.$className.'.php',
        $className,
        $cases,
    );
};
/**
 * @param list<array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}> $values
 * @return list<array{assertion: int, tag: string, value: array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}, expected: string}>
 */
$invalidCases = static fn (array $values): array => array_map(
    static function (mixed $value): array {
        assert(is_array($value));

        return ['assertion' => 0, 'tag' => 'en', 'value' => $value, 'expected' => Midnight\Intl\Exception\RangeError::class];
    },
    $values,
);
/** @var array<string, FixturePipeline> $fixturePipelines */
$fixturePipelines = [
    'test/intl402/Locale/constructor-unicode-ext-invalid.js' => new IdentifierRejectionPipeline(
        $assertionIdentities,
        $test262Revision,
        $ecma402Revision,
        'tests/Test262/Generated/ConstructorUnicodeExtensionInvalidTest.php',
        'ConstructorUnicodeExtensionInvalidTest',
    ),
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
    'test/intl402/Locale/reject-duplicate-variants.js' => new IdentifierRejectionPipeline(
        $assertionIdentities,
        $test262Revision,
        $ecma402Revision,
        'tests/Test262/Generated/RejectDuplicateVariantsTest.php',
        'RejectDuplicateVariantsTest',
    ),
    'test/intl402/Locale/reject-duplicate-variants-in-tlang.js' => new IdentifierRejectionPipeline(
        $assertionIdentities,
        $test262Revision,
        $ecma402Revision,
        'tests/Test262/Generated/RejectDuplicateVariantsInTlangTest.php',
        'RejectDuplicateVariantsInTlangTest',
    ),
    'test/intl402/Locale/constructor-options-script-valid.js' => new ConstructorFixturePipeline(
        new ConstructorOptionsScriptTranslator($assertionIdentities),
        $assertionIdentities,
        $representations,
        $test262Revision,
        $ecma402Revision,
    ),
    'test/intl402/Locale/constructor-options-script-valid-undefined.js' => new UndefinedConstructorOptionPipeline(
        $assertionIdentities,
        $representations,
        $test262Revision,
        $ecma402Revision,
        'script',
        'tests/Test262/Generated/ConstructorOptionsScriptValidUndefinedTest.php',
        'ConstructorOptionsScriptValidUndefinedTest',
    ),
    'test/intl402/Locale/constructor-options-language-grandfathered.js' => $mappedOptionPipeline('language', 'language-grandfathered', 'ConstructorOptionsLanguageGrandfatheredTest', [
        ['assertion' => 0, 'tag' => 'nb', 'value' => ['type' => 'string', 'value' => 'no-bok'], 'expected' => Midnight\Intl\Exception\RangeError::class],
        ['assertion' => 1, 'tag' => 'nb', 'value' => ['type' => 'string', 'value' => 'no-bok'], 'expected' => Midnight\Intl\Exception\RangeError::class],
    ]),
    'test/intl402/Locale/constructor-options-language-invalid.js' => $mappedOptionPipeline('language', 'language-invalid', 'ConstructorOptionsLanguageInvalidTest', $invalidCases(array_map(
        static fn (string|int $value): array => ['type' => is_int($value) ? 'int' : 'string', 'value' => $value],
        ['', 'a', 'ab7', 'notalanguage', 'undefined', 'root', 'fr-Latn', 'fr-FR', 'sa-vaidika', 'fr-a-asdf', 'fr-x-private', 'i-klingon', 'zh-min', 'zh-min-nan', 'abcd-US', 'abcde-US', 'abcdef-US', 'abcdefg-US', 'abcdefgh-US', 7],
    ))),
    'test/intl402/Locale/constructor-options-language-valid-undefined.js' => $mappedOptionPipeline('language', 'language-valid-undefined', 'ConstructorOptionsLanguageValidUndefinedTest', [
        ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'undefined'], 'expected' => 'en'],
        ['assertion' => 1, 'tag' => 'en-US', 'value' => ['type' => 'undefined'], 'expected' => 'en-US'],
        ['assertion' => 2, 'tag' => 'en-els', 'value' => ['type' => 'undefined'], 'expected' => Midnight\Intl\Exception\RangeError::class],
    ]),
    'test/intl402/Locale/constructor-options-language-valid.js' => $mappedOptionPipeline('language', 'language-valid', 'ConstructorOptionsLanguageValidTest', array_merge(
        [
            ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'stringable', 'value' => 'de'], 'expected' => 'de'],
            ['assertion' => 1, 'tag' => 'en-US', 'value' => ['type' => 'stringable', 'value' => 'de'], 'expected' => 'de-US'],
            ['assertion' => 2, 'tag' => 'en-els', 'value' => ['type' => 'stringable', 'value' => 'de'], 'expected' => Midnight\Intl\Exception\RangeError::class],
        ],
        array_merge(...array_map(static fn (array $value): array => array_map(
            static fn (int $assertion, string $tag): array => ['assertion' => $assertion, 'tag' => $tag, 'value' => $value, 'expected' => Midnight\Intl\Exception\RangeError::class],
            [3, 4, 5],
            ['en', 'en-US', 'en-els'],
        ), [['type' => 'null'], ['type' => 'string', 'value' => 'zh-cmn'], ['type' => 'string', 'value' => 'ZH-CMN'], ['type' => 'string', 'value' => 'abcd']]))
    )),
    'test/intl402/Locale/constructor-options-region-invalid.js' => $mappedOptionPipeline('region', 'region-invalid', 'ConstructorOptionsRegionInvalidTest', $invalidCases(array_map(
        static fn (string|int $value): array => ['type' => is_int($value) ? 'int' : 'string', 'value' => $value],
        ['', 'a', 'abc', 'a7', 'notaregion', 'SA-vaidika', 'SA-a-asdf', 'SA-x-private', 'ary-Arab', 'Latn-SA', 'Latn-vaidika', 'Latn-a-asdf', 'Latn-x-private', 7],
    ))),
    'test/intl402/Locale/constructor-options-region-valid.js' => $mappedOptionPipeline('region', 'region-valid', 'ConstructorOptionsRegionValidTest', array_merge(...array_map(
        static fn (array $row): array => array_map(
            static fn (int $assertion, string $tag, string $expected): array => ['assertion' => $assertion, 'tag' => $tag, 'value' => $row[0], 'expected' => $expected],
            [0, 1, 2, 3],
            ['en', 'en-US', 'en-u-ca-gregory', 'en-US-u-ca-gregory'],
            $row[1],
        ),
        [
            [['type' => 'undefined'], ['en', 'en-US', 'en-u-ca-gregory', 'en-US-u-ca-gregory']],
            [['type' => 'string', 'value' => 'FR'], ['en-FR', 'en-FR', 'en-FR-u-ca-gregory', 'en-FR-u-ca-gregory']],
            [['type' => 'string', 'value' => '554'], ['en-NZ', 'en-NZ', 'en-NZ-u-ca-gregory', 'en-NZ-u-ca-gregory']],
            [['type' => 'int', 'value' => 554], ['en-NZ', 'en-NZ', 'en-NZ-u-ca-gregory', 'en-NZ-u-ca-gregory']],
        ],
    ))),
    'test/intl402/Locale/constructor-options-script-invalid.js' => $mappedOptionPipeline('script', 'script-invalid', 'ConstructorOptionsScriptInvalidTest', $invalidCases(array_map(
        static fn (string|int $value): array => ['type' => is_int($value) ? 'int' : 'string', 'value' => $value],
        ['', 'a', 'ab', 'abc', 'abc7', 'notascript', 'undefined', "Bal\u{0130}", "Bal\u{0131}", 'ary-Arab', 'Latn-SA', 'Latn-vaidika', 'Latn-a-asdf', 'Latn-x-private', 7],
    ))),
    'test/intl402/Locale/constructor-options-variants-invalid.js' => $mappedOptionPipeline('variants', 'variants-invalid', 'ConstructorOptionsVariantsInvalidTest', $invalidCases(array_map(
        static fn (string $value): array => ['type' => 'string', 'value' => $value],
        ['', 'a', '1', 'ab', '2x', 'abc', '3xy', 'abcd', 'abcdefghi', 'GB-scouse', 'fonipa-fonipa', 'fonipa-valencia-Fonipa', '-', '-spanglis', 'spanglis-', '-spanglis-oxendict', 'spanglis-oxendict-', 'spanglis--oxendict'],
    ))),
    'test/intl402/Locale/constructor-options-variants-valid.js' => $mappedOptionPipeline('variants', 'variants-valid', 'ConstructorOptionsVariantsValidTest', array_merge(...array_map(
        static fn (array $row): array => array_map(
            static fn (int $assertion, string $tag, string $expected): array => ['assertion' => $assertion, 'tag' => $tag, 'value' => $row[1], 'expected' => $expected],
            [0, 1, 2, 3],
            [$row[0], $row[0].'-fonipa', $row[0].'-u-ca-gregory', $row[0].'-fonipa-u-ca-gregory'],
            $row[2],
        ),
        [
            ['en', ['type' => 'undefined'], ['en', 'en-fonipa', 'en-u-ca-gregory', 'en-fonipa-u-ca-gregory']],
            ['en', ['type' => 'string', 'value' => 'spanglis'], ['en-spanglis', 'en-spanglis', 'en-spanglis-u-ca-gregory', 'en-spanglis-u-ca-gregory']],
            ['xx', ['type' => 'string', 'value' => '1xyz'], ['xx-1xyz', 'xx-1xyz', 'xx-1xyz-u-ca-gregory', 'xx-1xyz-u-ca-gregory']],
            ['xx', ['type' => 'string', 'value' => '1234'], ['xx-1234', 'xx-1234', 'xx-1234-u-ca-gregory', 'xx-1234-u-ca-gregory']],
            ['xx', ['type' => 'string', 'value' => 'abcde'], ['xx-abcde', 'xx-abcde', 'xx-abcde-u-ca-gregory', 'xx-abcde-u-ca-gregory']],
            ['xx', ['type' => 'string', 'value' => '12345678'], ['xx-12345678', 'xx-12345678', 'xx-12345678-u-ca-gregory', 'xx-12345678-u-ca-gregory']],
            ['xx', ['type' => 'string', 'value' => '1xyz-1234-abcde-12345678'], ['xx-1234-12345678-1xyz-abcde', 'xx-1234-12345678-1xyz-abcde', 'xx-1234-12345678-1xyz-abcde-u-ca-gregory', 'xx-1234-12345678-1xyz-abcde-u-ca-gregory']],
            ['en', ['type' => 'string', 'value' => 'spanglis-oxendict'], ['en-oxendict-spanglis', 'en-oxendict-spanglis', 'en-oxendict-spanglis-u-ca-gregory', 'en-oxendict-spanglis-u-ca-gregory']],
        ],
    ))),
    'test/intl402/Locale/constructor-apply-options-canonicalizes-twice.js' => $mappedOptionPipeline('language', 'canonicalizes-twice', 'ConstructorApplyOptionsCanonicalizesTwiceTest', [
        ['assertion' => 0, 'tag' => 'und-Armn-SU', 'value' => ['type' => 'string', 'value' => 'ru'], 'expected' => 'ru-Armn-AM'],
    ]),
    'test/intl402/Locale/constructor-getter-order.js' => new OptionObservationPipeline(
        $assertionIdentities,
        $test262Revision,
        $ecma402Revision,
        'tests/Test262/Generated/ConstructorGetterOrderTest.php',
        'ConstructorGetterOrderTest',
        'order',
    ),
    'test/intl402/Locale/constructor-options-throwing-getters.js' => new OptionObservationPipeline(
        $assertionIdentities,
        $test262Revision,
        $ecma402Revision,
        'tests/Test262/Generated/ConstructorOptionsThrowingGettersTest.php',
        'ConstructorOptionsThrowingGettersTest',
        'throws',
        ['language', 'script', 'region', 'variants', 'calendar', 'collation', 'firstDayOfWeek', 'hourCycle', 'caseFirst', 'numeric', 'numberingSystem'],
    ),
    'test/intl402/Locale/constructor-locale-object.js' => new LocaleObjectPipeline(
        $assertionIdentities,
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
