<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalogContext;

/** @return array<string, FixturePipeline> */
return static function (FixtureCatalogContext $context): array {
    $mappedOptionPipeline = $context->mappedOptionPipeline;
    $invalidCases = $context->invalidCases;
    $stringValue = $context->stringValue;
    $optionCases = $context->optionCases;

    return [
        'test/intl402/Locale/constructor-options-firstDayOfWeek-invalid.js' => $mappedOptionPipeline(
            'firstDayOfWeek',
            '43fb84564abe6ad4696abb989ba45da508622a19589b20bc166a076a54e88bd2',
            'ConstructorOptionsFirstDayOfWeekInvalidTest',
            $invalidCases(array_map($stringValue, ['', 'm', 'mo', 'longerThan8Chars'])),
        ),
        'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js' => $mappedOptionPipeline(
            'firstDayOfWeek',
            'f08f24636a0f4c6446925f82f71c176128c208618ea899312bc37fc6862f1d3f',
            'ConstructorOptionsFirstDayOfWeekValidTest',
            $optionCases(
                array_map(static fn(array $row): array => [
                    is_int($row[0])
                        ? ['type' => 'int', 'value' => $row[0]]
                        : (
                            is_bool($row[0])
                                ? ['type' => 'bool', 'value' => $row[0]]
                                : ($row[0] === null ? ['type' => 'null'] : ['type' => 'string', 'value' => $row[0]])
                        ),
                    $row[1],
                ], [
                    ['mon',                  'en-u-fw-mon'],
                    ['tue',                  'en-u-fw-tue'],
                    ['wed',                  'en-u-fw-wed'],
                    ['thu',                  'en-u-fw-thu'],
                    ['fri',                  'en-u-fw-fri'],
                    ['sat',                  'en-u-fw-sat'],
                    ['sun',                  'en-u-fw-sun'],
                    ['1',                    'en-u-fw-mon'],
                    ['2',                    'en-u-fw-tue'],
                    ['3',                    'en-u-fw-wed'],
                    ['4',                    'en-u-fw-thu'],
                    ['5',                    'en-u-fw-fri'],
                    ['6',                    'en-u-fw-sat'],
                    ['7',                    'en-u-fw-sun'],
                    ['0',                    'en-u-fw-sun'],
                    [1,                      'en-u-fw-mon'],
                    [2,                      'en-u-fw-tue'],
                    [3,                      'en-u-fw-wed'],
                    [4,                      'en-u-fw-thu'],
                    [5,                      'en-u-fw-fri'],
                    [6,                      'en-u-fw-sat'],
                    [7,                      'en-u-fw-sun'],
                    [0,                      'en-u-fw-sun'],
                    [true,                   'en-u-fw'],
                    [false,                  'en-u-fw-false'],
                    [null,                   'en-u-fw-null'],
                    ['primidi',              'en-u-fw-primidi'],
                    ['duodi',                'en-u-fw-duodi'],
                    ['tridi',                'en-u-fw-tridi'],
                    ['quartidi',             'en-u-fw-quartidi'],
                    ['quintidi',             'en-u-fw-quintidi'],
                    ['sextidi',              'en-u-fw-sextidi'],
                    ['septidi',              'en-u-fw-septidi'],
                    ['octidi',               'en-u-fw-octidi'],
                    ['nonidi',               'en-u-fw-nonidi'],
                    ['decadi',               'en-u-fw-decadi'],
                    ['frank',                'en-u-fw-frank'],
                    ['yungfong',             'en-u-fw-yungfong'],
                    ['yung-fong',            'en-u-fw-yung-fong'],
                    ['tang',                 'en-u-fw-tang'],
                    ['frank-yung-fong-tang', 'en-u-fw-frank-yung-fong-tang'],
                ]),
                [
                    [0, 'en',          null],
                    [1, 'en-u-fw-WED', null],
                ],
            ),
        ),
    ];
};
