<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCases;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;

/**
 * @return array<string, FixturePipeline>
 */
return static function (FixtureCatalog $catalog): array {
    return [
        'test/intl402/Locale/getters.js' => $catalog->mappedState('8e0b947b19c9ba9b376341462d97391acd7d570dd7143c4c9fcb9b2a2646a615', [
            [
                'tag' => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
                'expectations' => FixtureCases::state(0, [
                    ['toString',        'de-Latn-DE-1996-fonipa-u-ca-gregory-co-phonebk-hc-h23-kf-kn-false-nu-latn'],
                    ['baseName',        'de-Latn-DE-1996-fonipa'],
                    ['language',        'de'],
                    ['script',          'Latn'],
                    ['region',          'DE'],
                    ['variants',        '1996-fonipa'],
                    ['calendar',        'gregory'],
                    ['collation',       'phonebk'],
                    ['hourCycle',       'h23'],
                    ['caseFirst',       ''],
                    ['numeric',         false],
                    ['numberingSystem', 'latn'],
                ]),
            ],
            [
                'tag' => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
                'options' => [
                    'language' => 'ja',
                    'script' => 'jpan',
                    'region' => 'jp',
                    'variants' => 'Hepburn',
                    'calendar' => 'japanese',
                    'collation' => 'search',
                    'hourCycle' => 'h24',
                    'caseFirst' => 'false',
                    'numeric' => 'true',
                    'numberingSystem' => 'jpanfin',
                ],
                'expectations' => FixtureCases::state(12, [
                    ['toString',        'ja-Jpan-JP-hepburn-u-ca-japanese-co-search-hc-h24-kf-false-kn-nu-jpanfin'],
                    ['baseName',        'ja-Jpan-JP-hepburn'],
                    ['language',        'ja'],
                    ['script',          'Jpan'],
                    ['region',          'JP'],
                    ['variants',        'hepburn'],
                    ['calendar',        'japanese'],
                    ['collation',       'search'],
                    ['hourCycle',       'h24'],
                    ['caseFirst',       'false'],
                    ['numeric',         true],
                    ['numberingSystem', 'jpanfin'],
                ]),
            ],
            [
                'tag' => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
                'options' => ['language' => 'fr', 'region' => 'ca', 'collation' => 'standard', 'hourCycle' => 'h11'],
                'expectations' => FixtureCases::state(24, [
                    ['toString',        'fr-Latn-CA-1996-fonipa-u-ca-gregory-co-standard-hc-h11-kf-kn-false-nu-latn'],
                    ['baseName',        'fr-Latn-CA-1996-fonipa'],
                    ['language',        'fr'],
                    ['script',          'Latn'],
                    ['region',          'CA'],
                    ['variants',        '1996-fonipa'],
                    ['calendar',        'gregory'],
                    ['collation',       'standard'],
                    ['hourCycle',       'h11'],
                    ['caseFirst',       ''],
                    ['numeric',         false],
                    ['numberingSystem', 'latn'],
                ]),
            ],
            [
                'tag' => 'und',
                'expectations' => FixtureCases::state(36, [
                    ['toString', 'und'],
                    ['baseName', 'und'],
                    ['language', 'und'],
                    ['script', null],
                    ['region', null],
                    ['variants', null],
                ]),
            ],
            [
                'tag' => 'und-US-u-co-emoji',
                'expectations' => FixtureCases::state(42, [
                    ['toString',  'und-US-u-co-emoji'],
                    ['baseName',  'und-US'],
                    ['language',  'und'],
                    ['script',    null],
                    ['region',    'US'],
                    ['variants',  null],
                    ['collation', 'emoji'],
                ]),
            ],
        ]),
        'test/intl402/Locale/prototype/calendar/canonicalize.js' => $catalog->mappedState('f822a4c333493c03b953eabab70fd3cb65fdd35c2052175041f382c1d630fce1', [[
            'tag' => 'en',
            'options' => ['calendar' => 'islamicc'],
            'expectations' => FixtureCases::state(0, [
                ['toString', 'en-u-ca-islamic-civil'],
                ['calendar', 'islamic-civil'],
            ]),
        ]]),
        'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js' => $catalog->mappedState('07f9babd1527066e864efb8b6b2d102a753a87c0b3324ffc1e9c17782d763c51', array_map(static fn(string $day): array => [
            'tag' => 'en-u-fw-' . $day,
            'expectations' => [['assertion' => 0, 'property' => 'firstDayOfWeek', 'expected' => $day]],
        ], ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'])),
        'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js' => $catalog->mappedState(
            '7cc86612c8c41133e3e17649c65b96f923604de8d02ff578398a1433b0dcc878',
            array_merge(...array_map(static fn(array $row): array => [
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
            ], [
                ['mon', 'mon'],
                ['tue', 'tue'],
                ['wed', 'wed'],
                ['thu', 'thu'],
                ['fri', 'fri'],
                ['sat', 'sat'],
                ['sun', 'sun'],
                ['1', 'mon'],
                ['2', 'tue'],
                ['3', 'wed'],
                ['4', 'thu'],
                ['5', 'fri'],
                ['6', 'sat'],
                ['7', 'sun'],
                ['0', 'sun'],
                [1, 'mon'],
                [2, 'tue'],
                [3, 'wed'],
                [4, 'thu'],
                [5, 'fri'],
                [6, 'sat'],
                [7, 'sun'],
                [0, 'sun'],
            ])),
        ),
        'test/intl402/Locale/constructor-options-canonicalized.js' => $catalog->mappedState(
            '5e978ad0e8df3b258dbec4af646532a712c0a28ac906a245f7c28450211d0613',
            array_merge(...array_map(static fn(array $row): array => [
                [
                    'tag' => 'en-u-ca-' . $row[1],
                    'expectations' => [['assertion' => 0, 'property' => 'calendar', 'expected' => $row[1]]],
                ],
                [
                    'tag' => 'en',
                    'options' => ['calendar' => $row[1]],
                    'expectations' => [['assertion' => 1, 'property' => 'calendar', 'expected' => $row[1]]],
                ],
                [
                    'tag' => 'en-u-ca-' . $row[0],
                    'expectations' => [['assertion' => 2, 'property' => 'calendar', 'expected' => $row[1]]],
                ],
                [
                    'tag' => 'en',
                    'options' => ['calendar' => $row[0]],
                    'expectations' => [['assertion' => 3, 'property' => 'calendar', 'expected' => $row[1]]],
                ],
            ], [
                ['islamicc',            'islamic-civil'],
                ['ethiopic-amete-alem', 'ethioaa'],
            ])),
        ),
        'test/intl402/Locale/constructor-options-calendar-invalid.js' => $catalog->mappedOption(
            'calendar',
            '2bc75ead4cdd03ab2eb742930d73395832134dd5c69ab4b0c00418c351e26b1a',
            FixtureCases::invalid(array_map(FixtureCases::string(...), [
                '',
                'a',
                'ab',
                'abcdefghi',
                'abc-abcdefghi',
            ])),
        ),
        'test/intl402/Locale/constructor-options-calendar-valid.js' => $catalog->mappedOption(
            'calendar',
            'e30190ad76b38bdd07d9ce24529ac7b34d6da320e0df07272dd57195e2084517',
            FixtureCases::options(FixtureCases::forKeyword('ca'), [
                [0, 'en',              null],
                [1, 'en-u-ca-gregory', null],
            ]),
        ),
        'test/intl402/Locale/constructor-options-collation-invalid.js' => $catalog->mappedOption(
            'collation',
            'bfa787a24721a492dbfc3a4cf18987a6ceb90b31ffe25a203d38978ac1954c80',
            FixtureCases::invalid(array_map(FixtureCases::string(...), [
                '',
                'a',
                'ab',
                'abcdefghi',
                'abc-abcdefghi',
            ])),
        ),
        'test/intl402/Locale/constructor-options-collation-valid.js' => $catalog->mappedOption(
            'collation',
            'e1b6b330d62172e723ac79708bdd013c9559d80f1504e8057a86a74e78b08e4a',
            FixtureCases::options(FixtureCases::forKeyword('co'), [
                [0, 'en',              null],
                [1, 'en-u-co-gregory', null],
            ]),
        ),
    ];
};
