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
use Midnight\Intl\Tools\Test262\MappedLocaleStatePipeline;
use Midnight\Intl\Tools\Test262\OptionObservationPipeline;
use Midnight\Intl\Tools\Test262\SourceBoundFixturePipeline;
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
/** @param list<string> $phpRepresentations */
$sourceBoundPipeline = static function (
    FixturePipeline $pipeline,
    array $phpRepresentations,
    string $sourceSha256,
) use ($assertionIdentities): SourceBoundFixturePipeline {
    /** @var list<string> $phpRepresentations */
    return new SourceBoundFixturePipeline(
        $pipeline,
        $assertionIdentities,
        $phpRepresentations,
        $sourceSha256,
    );
};
/**
 * @param list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string|bool, property?: string}> $cases
 */
$mappedOptionPipeline = static function (
    string $optionName,
    string $sourceSha256,
    string $className,
    array $cases,
) use ($assertionIdentities, $representations, $test262Revision, $ecma402Revision, $sourceBoundPipeline): SourceBoundFixturePipeline {
    /** @var list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string|bool, property?: string}> $cases */
    return $sourceBoundPipeline(
        new MappedConstructorOptionPipeline(
            $assertionIdentities,
            $representations,
            $test262Revision,
            $ecma402Revision,
            $optionName,
            'tests/Test262/Generated/'.$className.'.php',
            $className,
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
$invalidCases = static fn (array $values): array => array_map(
    static function (mixed $value): array {
        assert(is_array($value));

        return ['assertion' => 0, 'tag' => 'en', 'value' => $value, 'expected' => Midnight\Intl\Exception\RangeError::class];
    },
    $values,
);

/** @return array{type: 'string', value: string} */
$stringValue = static fn (string $value): array => ['type' => 'string', 'value' => $value];

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
                'expected' => $property === null ? $expected : $expected,
            ];
            if ($property !== null) {
                $case['property'] = $property;
            }
            $cases[] = $case;
        }
    }

    return $cases;
};

$openKeywordValues = array_map(
    static fn (array $row): array => [$stringValue($row[0]), $row[1]],
    [
        ['abc', 'en-u-%s-abc'],
        ['abcd', 'en-u-%s-abcd'],
        ['abcde', 'en-u-%s-abcde'],
        ['abcdef', 'en-u-%s-abcdef'],
        ['abcdefg', 'en-u-%s-abcdefg'],
        ['abcdefgh', 'en-u-%s-abcdefgh'],
        ['12345678', 'en-u-%s-12345678'],
        ['1234abcd', 'en-u-%s-1234abcd'],
        ['1234abcd-abc123', 'en-u-%s-1234abcd-abc123'],
    ],
);

/**
 * @param non-empty-string $key
 * @return list<array{array<string, mixed>, string}>
 */
$forKeyword = static fn (string $key): array => array_map(
    static fn (array $row): array => [$row[0], sprintf($row[1], $key)],
    $openKeywordValues,
);

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
$mappedStatePipeline = static function (
    string $sourceSha256,
    string $generatedPath,
    string $className,
    array $scenarios,
) use ($assertionIdentities, $test262Revision, $ecma402Revision, $sourceBoundPipeline): SourceBoundFixturePipeline {
    /** @var list<array{tag: string, options?: array<string, mixed>, expectations: list<array{assertion: int, property: string, expected: string|bool|null}>}> $scenarios */
    return $sourceBoundPipeline(
        new MappedLocaleStatePipeline(
            $assertionIdentities,
            $test262Revision,
            $ecma402Revision,
            $generatedPath,
            $className,
            $scenarios,
        ),
        ['direct', 'associative_array', 'plain_object'],
        $sourceSha256,
    );
};
/** @var array<string, FixturePipeline> $fixturePipelines */
$fixturePipelines = [
    'test/intl402/Locale/getters.js' => $mappedStatePipeline(
        '8e0b947b19c9ba9b376341462d97391acd7d570dd7143c4c9fcb9b2a2646a615',
        'tests/Test262/Generated/GettersTest.php',
        'GettersTest',
        [
            [
                'tag' => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
                'expectations' => $stateExpectations(0, [
                    ['toString', 'de-Latn-DE-1996-fonipa-u-ca-gregory-co-phonebk-hc-h23-kf-kn-false-nu-latn'],
                    ['baseName', 'de-Latn-DE-1996-fonipa'], ['language', 'de'], ['script', 'Latn'],
                    ['region', 'DE'], ['variants', '1996-fonipa'], ['calendar', 'gregory'],
                    ['collation', 'phonebk'], ['hourCycle', 'h23'], ['caseFirst', ''],
                    ['numeric', false], ['numberingSystem', 'latn'],
                ]),
            ],
            [
                'tag' => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
                'options' => [
                    'language' => 'ja', 'script' => 'jpan', 'region' => 'jp', 'variants' => 'Hepburn',
                    'calendar' => 'japanese', 'collation' => 'search', 'hourCycle' => 'h24',
                    'caseFirst' => 'false', 'numeric' => 'true', 'numberingSystem' => 'jpanfin',
                ],
                'expectations' => $stateExpectations(12, [
                    ['toString', 'ja-Jpan-JP-hepburn-u-ca-japanese-co-search-hc-h24-kf-false-kn-nu-jpanfin'],
                    ['baseName', 'ja-Jpan-JP-hepburn'], ['language', 'ja'], ['script', 'Jpan'],
                    ['region', 'JP'], ['variants', 'hepburn'], ['calendar', 'japanese'],
                    ['collation', 'search'], ['hourCycle', 'h24'], ['caseFirst', 'false'],
                    ['numeric', true], ['numberingSystem', 'jpanfin'],
                ]),
            ],
            [
                'tag' => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
                'options' => ['language' => 'fr', 'region' => 'ca', 'collation' => 'standard', 'hourCycle' => 'h11'],
                'expectations' => $stateExpectations(24, [
                    ['toString', 'fr-Latn-CA-1996-fonipa-u-ca-gregory-co-standard-hc-h11-kf-kn-false-nu-latn'],
                    ['baseName', 'fr-Latn-CA-1996-fonipa'], ['language', 'fr'], ['script', 'Latn'],
                    ['region', 'CA'], ['variants', '1996-fonipa'], ['calendar', 'gregory'],
                    ['collation', 'standard'], ['hourCycle', 'h11'], ['caseFirst', ''],
                    ['numeric', false], ['numberingSystem', 'latn'],
                ]),
            ],
            [
                'tag' => 'und',
                'expectations' => $stateExpectations(36, [
                    ['toString', 'und'], ['baseName', 'und'], ['language', 'und'],
                    ['script', null], ['region', null], ['variants', null],
                ]),
            ],
            [
                'tag' => 'und-US-u-co-emoji',
                'expectations' => $stateExpectations(42, [
                    ['toString', 'und-US-u-co-emoji'], ['baseName', 'und-US'], ['language', 'und'],
                    ['script', null], ['region', 'US'], ['variants', null], ['collation', 'emoji'],
                ]),
            ],
        ],
    ),
    'test/intl402/Locale/prototype/calendar/canonicalize.js' => $mappedStatePipeline(
        'f822a4c333493c03b953eabab70fd3cb65fdd35c2052175041f382c1d630fce1',
        'tests/Test262/Generated/CalendarCanonicalizeTest.php',
        'CalendarCanonicalizeTest',
        [[
            'tag' => 'en',
            'options' => ['calendar' => 'islamicc'],
            'expectations' => $stateExpectations(0, [
                ['toString', 'en-u-ca-islamic-civil'],
                ['calendar', 'islamic-civil'],
            ]),
        ]],
    ),
    'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js' => $mappedStatePipeline(
        '07f9babd1527066e864efb8b6b2d102a753a87c0b3324ffc1e9c17782d763c51',
        'tests/Test262/Generated/FirstDayOfWeekValidIdentifierTest.php',
        'FirstDayOfWeekValidIdentifierTest',
        array_map(
            static fn (string $day): array => [
                'tag' => 'en-u-fw-'.$day,
                'expectations' => [['assertion' => 0, 'property' => 'firstDayOfWeek', 'expected' => $day]],
            ],
            ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
        ),
    ),
    'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js' => $mappedStatePipeline(
        '7cc86612c8c41133e3e17649c65b96f923604de8d02ff578398a1433b0dcc878',
        'tests/Test262/Generated/FirstDayOfWeekValidOptionsTest.php',
        'FirstDayOfWeekValidOptionsTest',
        array_merge(...array_map(
            static fn (array $row): array => [
                [
                    'tag' => 'en',
                    'options' => ['firstDayOfWeek' => $row[0]],
                    'expectations' => [['assertion' => 0, 'property' => 'firstDayOfWeek', 'expected' => $row[1]]],
                ],
                [
                    'tag' => 'en-u-fw-WED',
                    'options' => ['firstDayOfWeek' => $row[0]],
                    'expectations' => [['assertion' => 1, 'property' => 'firstDayOfWeek', 'expected' => $row[1]]],
                ],
            ],
            [
                ['mon', 'mon'], ['tue', 'tue'], ['wed', 'wed'], ['thu', 'thu'], ['fri', 'fri'],
                ['sat', 'sat'], ['sun', 'sun'], ['1', 'mon'], ['2', 'tue'], ['3', 'wed'],
                ['4', 'thu'], ['5', 'fri'], ['6', 'sat'], ['7', 'sun'], ['0', 'sun'],
                [1, 'mon'], [2, 'tue'], [3, 'wed'], [4, 'thu'], [5, 'fri'], [6, 'sat'], [7, 'sun'], [0, 'sun'],
            ],
        )),
    ),
    'test/intl402/Locale/constructor-options-calendar-invalid.js' => $mappedOptionPipeline('calendar', '2bc75ead4cdd03ab2eb742930d73395832134dd5c69ab4b0c00418c351e26b1a', 'ConstructorOptionsCalendarInvalidTest', $invalidCases(array_map(
        $stringValue,
        ['', 'a', 'ab', 'abcdefghi', 'abc-abcdefghi'],
    ))),
    'test/intl402/Locale/constructor-options-calendar-valid.js' => $mappedOptionPipeline('calendar', 'e30190ad76b38bdd07d9ce24529ac7b34d6da320e0df07272dd57195e2084517', 'ConstructorOptionsCalendarValidTest', $optionCases(
        $forKeyword('ca'),
        [[0, 'en', null], [1, 'en-u-ca-gregory', null]],
    )),
    'test/intl402/Locale/constructor-options-collation-invalid.js' => $mappedOptionPipeline('collation', 'bfa787a24721a492dbfc3a4cf18987a6ceb90b31ffe25a203d38978ac1954c80', 'ConstructorOptionsCollationInvalidTest', $invalidCases(array_map(
        $stringValue,
        ['', 'a', 'ab', 'abcdefghi', 'abc-abcdefghi'],
    ))),
    'test/intl402/Locale/constructor-options-collation-valid.js' => $mappedOptionPipeline('collation', 'e1b6b330d62172e723ac79708bdd013c9559d80f1504e8057a86a74e78b08e4a', 'ConstructorOptionsCollationValidTest', $optionCases(
        $forKeyword('co'),
        [[0, 'en', null], [1, 'en-u-co-gregory', null]],
    )),
    'test/intl402/Locale/constructor-options-firstDayOfWeek-invalid.js' => $mappedOptionPipeline('firstDayOfWeek', '43fb84564abe6ad4696abb989ba45da508622a19589b20bc166a076a54e88bd2', 'ConstructorOptionsFirstDayOfWeekInvalidTest', $invalidCases(array_map(
        $stringValue,
        ['', 'm', 'mo', 'longerThan8Chars'],
    ))),
    'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js' => $mappedOptionPipeline('firstDayOfWeek', 'f08f24636a0f4c6446925f82f71c176128c208618ea899312bc37fc6862f1d3f', 'ConstructorOptionsFirstDayOfWeekValidTest', $optionCases(
        array_map(
            static fn (array $row): array => [
                is_int($row[0]) ? ['type' => 'int', 'value' => $row[0]]
                    : (is_bool($row[0]) ? ['type' => 'bool', 'value' => $row[0]]
                        : ($row[0] === null ? ['type' => 'null'] : ['type' => 'string', 'value' => $row[0]])),
                $row[1],
            ],
            [
                ['mon', 'en-u-fw-mon'], ['tue', 'en-u-fw-tue'], ['wed', 'en-u-fw-wed'],
                ['thu', 'en-u-fw-thu'], ['fri', 'en-u-fw-fri'], ['sat', 'en-u-fw-sat'], ['sun', 'en-u-fw-sun'],
                ['1', 'en-u-fw-mon'], ['2', 'en-u-fw-tue'], ['3', 'en-u-fw-wed'], ['4', 'en-u-fw-thu'],
                ['5', 'en-u-fw-fri'], ['6', 'en-u-fw-sat'], ['7', 'en-u-fw-sun'], ['0', 'en-u-fw-sun'],
                [1, 'en-u-fw-mon'], [2, 'en-u-fw-tue'], [3, 'en-u-fw-wed'], [4, 'en-u-fw-thu'],
                [5, 'en-u-fw-fri'], [6, 'en-u-fw-sat'], [7, 'en-u-fw-sun'], [0, 'en-u-fw-sun'],
                [true, 'en-u-fw'], [false, 'en-u-fw-false'], [null, 'en-u-fw-null'],
                ['primidi', 'en-u-fw-primidi'], ['duodi', 'en-u-fw-duodi'], ['tridi', 'en-u-fw-tridi'],
                ['quartidi', 'en-u-fw-quartidi'], ['quintidi', 'en-u-fw-quintidi'], ['sextidi', 'en-u-fw-sextidi'],
                ['septidi', 'en-u-fw-septidi'], ['octidi', 'en-u-fw-octidi'], ['nonidi', 'en-u-fw-nonidi'],
                ['decadi', 'en-u-fw-decadi'], ['frank', 'en-u-fw-frank'], ['yungfong', 'en-u-fw-yungfong'],
                ['yung-fong', 'en-u-fw-yung-fong'], ['tang', 'en-u-fw-tang'],
                ['frank-yung-fong-tang', 'en-u-fw-frank-yung-fong-tang'],
            ],
        ),
        [[0, 'en', null], [1, 'en-u-fw-WED', null]],
    )),
    'test/intl402/Locale/constructor-options-hourcycle-invalid.js' => $mappedOptionPipeline('hourCycle', '5c72693abd40501d9911c66c0793e13f4dcc7fb32cd430e9f459feec8eaceade', 'ConstructorOptionsHourCycleInvalidTest', $invalidCases(array_map(
        $stringValue,
        ['', 'h', 'h00', 'h01', 'h10', 'h13', 'h22', 'h25', 'h48', 'h012', 'h120', "h12\0", 'H12'],
    ))),
    'test/intl402/Locale/constructor-options-hourcycle-valid.js' => $mappedOptionPipeline('hourCycle', 'ab8ee2541c2e2cba74b8c38be7eea57f90cdbd58be1e3ac8c50a6f04851f19b0', 'ConstructorOptionsHourCycleValidTest', array_merge(
        $optionCases(
            [
                [$stringValue('h11'), 'en-u-hc-h11'], [$stringValue('h12'), 'en-u-hc-h12'],
                [$stringValue('h23'), 'en-u-hc-h23'], [$stringValue('h24'), 'en-u-hc-h24'],
                [['type' => 'stringable', 'value' => 'h24'], 'en-u-hc-h24'],
            ],
            [[0, 'en', null], [1, 'en-u-hc-h00', null], [2, 'en-u-hc-h12', null]],
        ),
        array_map(
            static fn (array $value): array => ['assertion' => 3, 'tag' => 'en-u-hc-h00', 'value' => $value, 'expected' => $value['value'], 'property' => 'hourCycle'],
            [$stringValue('h11'), $stringValue('h12'), $stringValue('h23'), $stringValue('h24'), ['type' => 'stringable', 'value' => 'h24']],
        ),
    )),
    'test/intl402/Locale/constructor-options-casefirst-invalid.js' => $mappedOptionPipeline('caseFirst', '9148cb991c017d5dad7eaf1736cca3322d137ab1f960ae3bfaa4a9ab65e88a06', 'ConstructorOptionsCaseFirstInvalidTest', $invalidCases([
        ...array_map($stringValue, ['', 'u', 'Upper', "upper\0", 'uppercase', 'true']),
        ['type' => 'primitive', 'value' => '[object Object]'],
    ])),
    'test/intl402/Locale/constructor-options-casefirst-valid.js' => $mappedOptionPipeline('caseFirst', 'abb926d4d19763f5a4bf6169b2d156714c8300aac95b73b185026a259eac4449', 'ConstructorOptionsCaseFirstValidTest', array_merge(
        $optionCases(
            [
                [$stringValue('upper'), 'en-u-kf-upper'], [$stringValue('lower'), 'en-u-kf-lower'],
                [$stringValue('false'), 'en-u-kf-false'], [['type' => 'bool', 'value' => false], 'en-u-kf-false'],
                [['type' => 'primitive', 'value' => false], 'en-u-kf-false'],
            ],
            [[0, 'en', null], [1, 'en-u-kf-lower', null]],
        ),
        array_map(
            static fn (array $value): array => ['assertion' => 2, 'tag' => 'en-u-kf-lower', 'value' => $value, 'expected' => $value['value'] === false ? 'false' : $value['value'], 'property' => 'caseFirst'],
            [$stringValue('upper'), $stringValue('lower'), $stringValue('false'), ['type' => 'bool', 'value' => false], ['type' => 'primitive', 'value' => false]],
        ),
    )),
    'test/intl402/Locale/constructor-options-numeric-undefined.js' => $mappedOptionPipeline('numeric', 'df2e3eeab1d34aae0cdd35e65e7495c9616b5d11aeb01d8ab0827fddd3a190d0', 'ConstructorOptionsNumericUndefinedTest', [
        ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'undefined'], 'expected' => 'en'],
        ['assertion' => 1, 'tag' => 'en-u-kn-true', 'value' => ['type' => 'undefined'], 'expected' => 'en-u-kn'],
        ['assertion' => 2, 'tag' => 'en-u-kf-lower', 'value' => ['type' => 'undefined'], 'expected' => false, 'property' => 'numeric'],
    ]),
    'test/intl402/Locale/constructor-options-numeric-valid.js' => $mappedOptionPipeline('numeric', '1b2f6279e7c2187178a129266f020cece44c1a4e0099c27ddeec46ed8cfb90e0', 'ConstructorOptionsNumericValidTest', array_merge(...array_map(
        static fn (array $row): array => [
            ['assertion' => 0, 'tag' => 'en', 'value' => $row[0], 'expected' => $row[1] ? 'en-u-kn' : 'en-u-kn-false'],
            ['assertion' => 1, 'tag' => 'en-u-kn-true', 'value' => $row[0], 'expected' => $row[1] ? 'en-u-kn' : 'en-u-kn-false'],
            ['assertion' => 2, 'tag' => 'en-u-kf-lower', 'value' => $row[0], 'expected' => $row[1], 'property' => 'numeric'],
        ],
        [
            [['type' => 'bool', 'value' => false], false], [['type' => 'bool', 'value' => true], true],
            [['type' => 'null'], false], [['type' => 'int', 'value' => 0], false],
            [['type' => 'float', 'value' => 0.5], true], [$stringValue('true'), true],
            [$stringValue('false'), true], [['type' => 'object'], true],
        ],
    ))),
    'test/intl402/Locale/constructor-options-numberingsystem-invalid.js' => $mappedOptionPipeline('numberingSystem', '43c781dde99d7843e82ff4daf0a48825d41301749bfcc3a6ebd4ee3abe3ceb6d', 'ConstructorOptionsNumberingSystemInvalidTest', $invalidCases(array_map(
        $stringValue,
        ['', 'a', 'ab', 'abcdefghi', 'abc-abcdefghi', '!invalid!', '-latn-', 'latn-', 'latn--', 'latn-ca', 'latn-ca-', 'latn-ca-gregory'],
    ))),
    'test/intl402/Locale/constructor-options-numberingsystem-valid.js' => $mappedOptionPipeline('numberingSystem', 'd95df8c7fa8189de5f3e51d109130f02845fb7ba7c3c4aa7a132acb716d38862', 'ConstructorOptionsNumberingSystemValidTest', $optionCases(
        $forKeyword('nu'),
        [[0, 'en', null], [1, 'en-u-nu-latn', null]],
    )),
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
    'test/intl402/Locale/constructor-options-language-grandfathered.js' => $mappedOptionPipeline('language', 'c025cd8b7afbf52d8ee2b9c8be64dd25e45a3dc97b55d82bd95bde7f3ecc9f9a', 'ConstructorOptionsLanguageGrandfatheredTest', [
        ['assertion' => 0, 'tag' => 'nb', 'value' => ['type' => 'string', 'value' => 'no-bok'], 'expected' => Midnight\Intl\Exception\RangeError::class],
        ['assertion' => 1, 'tag' => 'nb', 'value' => ['type' => 'string', 'value' => 'no-bok'], 'expected' => Midnight\Intl\Exception\RangeError::class],
    ]),
    'test/intl402/Locale/constructor-options-language-invalid.js' => $mappedOptionPipeline('language', '3b12a5a117b10381878f3a851ef231904e662da92cc3b5d0aa981119daba4720', 'ConstructorOptionsLanguageInvalidTest', $invalidCases(array_map(
        static fn (string|int $value): array => ['type' => is_int($value) ? 'int' : 'string', 'value' => $value],
        ['', 'a', 'ab7', 'notalanguage', 'undefined', 'root', 'fr-Latn', 'fr-FR', 'sa-vaidika', 'fr-a-asdf', 'fr-x-private', 'i-klingon', 'zh-min', 'zh-min-nan', 'abcd-US', 'abcde-US', 'abcdef-US', 'abcdefg-US', 'abcdefgh-US', 7],
    ))),
    'test/intl402/Locale/constructor-options-language-valid-undefined.js' => $mappedOptionPipeline('language', '5b477146b9f9e57bce4d2fd67c4db4c05b1c17dfc58b3c06b8a52a96806dc8d0', 'ConstructorOptionsLanguageValidUndefinedTest', [
        ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'undefined'], 'expected' => 'en'],
        ['assertion' => 1, 'tag' => 'en-US', 'value' => ['type' => 'undefined'], 'expected' => 'en-US'],
        ['assertion' => 2, 'tag' => 'en-els', 'value' => ['type' => 'undefined'], 'expected' => Midnight\Intl\Exception\RangeError::class],
    ]),
    'test/intl402/Locale/constructor-options-language-valid.js' => $mappedOptionPipeline('language', 'b770d910f1c14445d8ef0d2c8981baffce6f20920181edd8dda506c4c4f25959', 'ConstructorOptionsLanguageValidTest', array_merge(
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
    'test/intl402/Locale/constructor-options-region-invalid.js' => $mappedOptionPipeline('region', 'eb88f909f874da2e065480c8cc2e86b8d6b4555dc8d80812d5691dac9b855569', 'ConstructorOptionsRegionInvalidTest', $invalidCases(array_map(
        static fn (string|int $value): array => ['type' => is_int($value) ? 'int' : 'string', 'value' => $value],
        ['', 'a', 'abc', 'a7', 'notaregion', 'SA-vaidika', 'SA-a-asdf', 'SA-x-private', 'ary-Arab', 'Latn-SA', 'Latn-vaidika', 'Latn-a-asdf', 'Latn-x-private', 7],
    ))),
    'test/intl402/Locale/constructor-options-region-valid.js' => $mappedOptionPipeline('region', '1d138f46632f20db49f2e6b96a2ca66cfc814d35ea94d5e93e6d6c50e8059f2d', 'ConstructorOptionsRegionValidTest', array_merge(...array_map(
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
    'test/intl402/Locale/constructor-options-script-invalid.js' => $mappedOptionPipeline('script', 'eaffbb2741f360729d4f732331525876c4eaa9736be43efe724b2953455e0412', 'ConstructorOptionsScriptInvalidTest', $invalidCases(array_map(
        static fn (string|int $value): array => ['type' => is_int($value) ? 'int' : 'string', 'value' => $value],
        ['', 'a', 'ab', 'abc', 'abc7', 'notascript', 'undefined', "Bal\u{0130}", "Bal\u{0131}", 'ary-Arab', 'Latn-SA', 'Latn-vaidika', 'Latn-a-asdf', 'Latn-x-private', 7],
    ))),
    'test/intl402/Locale/constructor-options-variants-invalid.js' => $mappedOptionPipeline('variants', 'b2c1b4589959a1c94ab97ab3049489289a649ef9324e021541250063654a991f', 'ConstructorOptionsVariantsInvalidTest', $invalidCases(array_map(
        static fn (string $value): array => ['type' => 'string', 'value' => $value],
        ['', 'a', '1', 'ab', '2x', 'abc', '3xy', 'abcd', 'abcdefghi', 'GB-scouse', 'fonipa-fonipa', 'fonipa-valencia-Fonipa', '-', '-spanglis', 'spanglis-', '-spanglis-oxendict', 'spanglis-oxendict-', 'spanglis--oxendict'],
    ))),
    'test/intl402/Locale/constructor-options-variants-valid.js' => $mappedOptionPipeline('variants', '3f636ca71f4f6a75e8f818b341707ac17ead7ba0e432c3a12877e3dd1cbdaf07', 'ConstructorOptionsVariantsValidTest', array_merge(...array_map(
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
    'test/intl402/Locale/constructor-apply-options-canonicalizes-twice.js' => $mappedOptionPipeline('language', 'aa542114d28bfdc89fe10ac51cde78837a8392ef1d52ecf47cbfcd9a5dec2fbb', 'ConstructorApplyOptionsCanonicalizesTwiceTest', [
        ['assertion' => 0, 'tag' => 'und-Armn-SU', 'value' => ['type' => 'string', 'value' => 'ru'], 'expected' => 'ru-Armn-AM'],
    ]),
    'test/intl402/Locale/constructor-getter-order.js' => $sourceBoundPipeline(
        new OptionObservationPipeline(
            $assertionIdentities,
            $test262Revision,
            $ecma402Revision,
            'tests/Test262/Generated/ConstructorGetterOrderTest.php',
            'ConstructorGetterOrderTest',
            'order',
        ),
        ['behavioral_object'],
        'ee7935bd44614b4c2095766bfc328b02c28264e04ac036bc6d93223db32b8044',
    ),
    'test/intl402/Locale/constructor-options-throwing-getters.js' => $sourceBoundPipeline(
        new OptionObservationPipeline(
            $assertionIdentities,
            $test262Revision,
            $ecma402Revision,
            'tests/Test262/Generated/ConstructorOptionsThrowingGettersTest.php',
            'ConstructorOptionsThrowingGettersTest',
            'throws',
            ['language', 'script', 'region', 'variants', 'calendar', 'collation', 'firstDayOfWeek', 'hourCycle', 'caseFirst', 'numeric', 'numberingSystem'],
        ),
        ['behavioral_object'],
        'c2b93ea685d76e9d43a1dc4339b298323d1cf8809e1ddf66e2b9e20d07eab882',
    ),
    'test/intl402/Locale/constructor-locale-object.js' => $sourceBoundPipeline(
        new LocaleObjectPipeline(
            $assertionIdentities,
            $test262Revision,
            $ecma402Revision,
        ),
        $representations,
        'c4c6ac019b341d0660e8f3869943e39312b45e21e36f178abef600505aef243d',
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
