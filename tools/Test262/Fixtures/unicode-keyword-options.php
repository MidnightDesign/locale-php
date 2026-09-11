<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCases;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;

/** @return array<string, FixturePipeline> */
return static function (FixtureCatalog $catalog): array {
    $mappedOptionPipeline = $catalog->mappedOption(...);
    $invalidCases = FixtureCases::invalid(...);
    $stringValue = FixtureCases::string(...);
    $optionCases = FixtureCases::options(...);
    $forKeyword = FixtureCases::forKeyword(...);

    return [
        'test/intl402/Locale/constructor-options-hourcycle-invalid.js' => $mappedOptionPipeline(
            'hourCycle',
            '5c72693abd40501d9911c66c0793e13f4dcc7fb32cd430e9f459feec8eaceade',
            $invalidCases(array_map($stringValue, [
                '',
                'h',
                'h00',
                'h01',
                'h10',
                'h13',
                'h22',
                'h25',
                'h48',
                'h012',
                'h120',
                "h12\0",
                'H12',
            ])),
        ),
        'test/intl402/Locale/constructor-options-hourcycle-valid.js' => $mappedOptionPipeline(
            'hourCycle',
            'ab8ee2541c2e2cba74b8c38be7eea57f90cdbd58be1e3ac8c50a6f04851f19b0',
            array_merge(
                $optionCases([
                    [$stringValue('h11'), 'en-u-hc-h11'],
                    [$stringValue('h12'), 'en-u-hc-h12'],
                    [$stringValue('h23'), 'en-u-hc-h23'],
                    [$stringValue('h24'), 'en-u-hc-h24'],
                    [['type' => 'stringable', 'value' => 'h24'], 'en-u-hc-h24'],
                ], [
                    [0, 'en',          null],
                    [1, 'en-u-hc-h00', null],
                    [2, 'en-u-hc-h12', null],
                ]),
                array_map(static fn(array $value): array => [
                    'assertion' => 3,
                    'tag' => 'en-u-hc-h00',
                    'value' => $value,
                    'expected' => $value['value'],
                    'property' => 'hourCycle',
                ], [
                    $stringValue('h11'),
                    $stringValue('h12'),
                    $stringValue('h23'),
                    $stringValue('h24'),
                    ['type' => 'stringable', 'value' => 'h24'],
                ]),
            ),
        ),
        'test/intl402/Locale/constructor-options-casefirst-invalid.js' => $mappedOptionPipeline(
            'caseFirst',
            '9148cb991c017d5dad7eaf1736cca3322d137ab1f960ae3bfaa4a9ab65e88a06',
            $invalidCases([
                ...array_map($stringValue, ['', 'u', 'Upper', "upper\0", 'uppercase', 'true']),
                ['type' => 'primitive', 'value' => '[object Object]'],
            ]),
        ),
        'test/intl402/Locale/constructor-options-casefirst-valid.js' => $mappedOptionPipeline(
            'caseFirst',
            'abb926d4d19763f5a4bf6169b2d156714c8300aac95b73b185026a259eac4449',
            array_merge(
                $optionCases([
                    [$stringValue('upper'), 'en-u-kf-upper'],
                    [$stringValue('lower'), 'en-u-kf-lower'],
                    [$stringValue('false'), 'en-u-kf-false'],
                    [['type' => 'bool', 'value' => false], 'en-u-kf-false'],
                    [['type' => 'primitive', 'value' => false], 'en-u-kf-false'],
                ], [
                    [0, 'en',            null],
                    [1, 'en-u-kf-lower', null],
                ]),
                array_map(static fn(array $value): array => [
                    'assertion' => 2,
                    'tag' => 'en-u-kf-lower',
                    'value' => $value,
                    'expected' => $value['value'] === false ? 'false' : $value['value'],
                    'property' => 'caseFirst',
                ], [
                    $stringValue('upper'),
                    $stringValue('lower'),
                    $stringValue('false'),
                    ['type' => 'bool', 'value' => false],
                    ['type' => 'primitive', 'value' => false],
                ]),
            ),
        ),
        'test/intl402/Locale/constructor-options-numeric-undefined.js' => $mappedOptionPipeline(
            'numeric',
            'df2e3eeab1d34aae0cdd35e65e7495c9616b5d11aeb01d8ab0827fddd3a190d0',
            [
                ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'undefined'], 'expected' => 'en'],
                [
                    'assertion' => 1,
                    'tag' => 'en-u-kn-true',
                    'value' => ['type' => 'undefined'],
                    'expected' => 'en-u-kn',
                ],
                [
                    'assertion' => 2,
                    'tag' => 'en-u-kf-lower',
                    'value' => ['type' => 'undefined'],
                    'expected' => false,
                    'property' => 'numeric',
                ],
            ],
        ),
        'test/intl402/Locale/constructor-options-numeric-valid.js' => $mappedOptionPipeline(
            'numeric',
            '1b2f6279e7c2187178a129266f020cece44c1a4e0099c27ddeec46ed8cfb90e0',
            array_merge(...array_map(static fn(array $row): array => [
                [
                    'assertion' => 0,
                    'tag' => 'en',
                    'value' => $row[0],
                    'expected' => $row[1] ? 'en-u-kn' : 'en-u-kn-false',
                ],
                [
                    'assertion' => 1,
                    'tag' => 'en-u-kn-true',
                    'value' => $row[0],
                    'expected' => $row[1] ? 'en-u-kn' : 'en-u-kn-false',
                ],
                [
                    'assertion' => 2,
                    'tag' => 'en-u-kf-lower',
                    'value' => $row[0],
                    'expected' => $row[1],
                    'property' => 'numeric',
                ],
            ], [
                [['type' => 'bool', 'value' => false], false],
                [['type' => 'bool', 'value' => true], true],
                [['type' => 'null'], false],
                [['type' => 'int', 'value' => 0], false],
                [['type' => 'float', 'value' => 0.5], true],
                [$stringValue('true'), true],
                [$stringValue('false'), true],
                [['type' => 'object'], true],
            ])),
        ),
        'test/intl402/Locale/constructor-options-numberingsystem-invalid.js' => $mappedOptionPipeline(
            'numberingSystem',
            '43c781dde99d7843e82ff4daf0a48825d41301749bfcc3a6ebd4ee3abe3ceb6d',
            $invalidCases(array_map($stringValue, [
                '',
                'a',
                'ab',
                'abcdefghi',
                'abc-abcdefghi',
                '!invalid!',
                '-latn-',
                'latn-',
                'latn--',
                'latn-ca',
                'latn-ca-',
                'latn-ca-gregory',
            ])),
        ),
        'test/intl402/Locale/constructor-options-numberingsystem-valid.js' => $mappedOptionPipeline(
            'numberingSystem',
            'd95df8c7fa8189de5f3e51d109130f02845fb7ba7c3c4aa7a132acb716d38862',
            $optionCases($forKeyword('nu'), [
                [0, 'en',           null],
                [1, 'en-u-nu-latn', null],
            ]),
        ),
    ];
};
