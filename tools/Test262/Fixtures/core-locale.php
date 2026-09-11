<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCases;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;

/** @return array<string, FixturePipeline> */
return static function (FixtureCatalog $catalog): array {
    $mappedOptionPipeline = $catalog->mappedOption(...);
    $invalidCases = FixtureCases::invalid(...);

    return [
        'test/intl402/Locale/likely-subtags-grandfathered.js' => $catalog->grandfatheredLikelySubtags(),
        'test/intl402/Locale/likely-subtags.js' => $catalog->likelySubtags(),
        'test/intl402/Locale/constructor-unicode-ext-invalid.js' => $catalog->identifierRejection(),
        'test/intl402/Locale/constructor-unicode-ext-valid.js' => $catalog->identifierCanonicalization(),
        'test/intl402/Locale/getters-missing.js' => $catalog->getters(),
        'test/intl402/Locale/reject-duplicate-variants.js' => $catalog->identifierRejection(),
        'test/intl402/Locale/reject-duplicate-variants-in-tlang.js' => $catalog->identifierRejection(),
        'test/intl402/Locale/constructor-options-script-valid.js' => $catalog->scriptConstructor(),
        'test/intl402/Locale/prototype/maximize/branding.js' => $catalog->localeMethod('maximize', 'branding'),
        'test/intl402/Locale/prototype/maximize/length.js' => $catalog->localeMethod('maximize', 'length'),
        'test/intl402/Locale/prototype/maximize/name.js' => $catalog->localeMethod('maximize', 'name'),
        'test/intl402/Locale/prototype/maximize/prop-desc.js' => $catalog->localeMethod('maximize', 'property'),
        'test/intl402/Locale/prototype/minimize/branding.js' => $catalog->localeMethod('minimize', 'branding'),
        'test/intl402/Locale/prototype/minimize/length.js' => $catalog->localeMethod('minimize', 'length'),
        'test/intl402/Locale/prototype/minimize/name.js' => $catalog->localeMethod('minimize', 'name'),
        'test/intl402/Locale/prototype/minimize/prop-desc.js' => $catalog->localeMethod('minimize', 'property'),
        'test/intl402/Locale/prototype/getTextInfo/branding.js' => $catalog->localeMethod('getTextInfo', 'branding'),
        'test/intl402/Locale/prototype/getTextInfo/name.js' => $catalog->localeMethod('getTextInfo', 'name'),
        'test/intl402/Locale/prototype/getTextInfo/output-object-keys.js' => $catalog->textInfo('keys'),
        'test/intl402/Locale/prototype/getTextInfo/output-object.js' => $catalog->textInfo('record'),
        'test/intl402/Locale/prototype/getTextInfo/prop-desc.js' => $catalog->localeMethod('getTextInfo', 'property'),
        'test/intl402/Locale/prototype/minimize/removing-likely-subtags-first-adds-likely-subtags.js' =>
            $catalog->removeLikelySubtags(),
        'test/intl402/Locale/constructor-options-script-valid-undefined.js' => $catalog->undefinedConstructorOption(
            'script',
        ),
        'test/intl402/Locale/constructor-options-language-grandfathered.js' => $mappedOptionPipeline(
            'language',
            'c025cd8b7afbf52d8ee2b9c8be64dd25e45a3dc97b55d82bd95bde7f3ecc9f9a',
            [
                [
                    'assertion' => 0,
                    'tag' => 'nb',
                    'value' => ['type' => 'string', 'value' => 'no-bok'],
                    'expected' => Midnight\Intl\Exception\RangeError::class,
                ],
                [
                    'assertion' => 1,
                    'tag' => 'nb',
                    'value' => ['type' => 'string', 'value' => 'no-bok'],
                    'expected' => Midnight\Intl\Exception\RangeError::class,
                ],
            ],
        ),
        'test/intl402/Locale/constructor-options-language-invalid.js' => $mappedOptionPipeline(
            'language',
            '3b12a5a117b10381878f3a851ef231904e662da92cc3b5d0aa981119daba4720',
            $invalidCases(array_map(static fn(string|int $value): array => [
                'type' => is_int($value) ? 'int' : 'string',
                'value' => $value,
            ], [
                '',
                'a',
                'ab7',
                'notalanguage',
                'undefined',
                'root',
                'fr-Latn',
                'fr-FR',
                'sa-vaidika',
                'fr-a-asdf',
                'fr-x-private',
                'i-klingon',
                'zh-min',
                'zh-min-nan',
                'abcd-US',
                'abcde-US',
                'abcdef-US',
                'abcdefg-US',
                'abcdefgh-US',
                7,
            ])),
        ),
        'test/intl402/Locale/constructor-options-language-valid-undefined.js' => $mappedOptionPipeline(
            'language',
            '5b477146b9f9e57bce4d2fd67c4db4c05b1c17dfc58b3c06b8a52a96806dc8d0',
            [
                ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'undefined'], 'expected' => 'en'],
                ['assertion' => 1, 'tag' => 'en-US', 'value' => ['type' => 'undefined'], 'expected' => 'en-US'],
                [
                    'assertion' => 2,
                    'tag' => 'en-els',
                    'value' => ['type' => 'undefined'],
                    'expected' => Midnight\Intl\Exception\RangeError::class,
                ],
            ],
        ),
        'test/intl402/Locale/constructor-options-language-valid.js' => $mappedOptionPipeline(
            'language',
            'b770d910f1c14445d8ef0d2c8981baffce6f20920181edd8dda506c4c4f25959',
            array_merge(
                [
                    [
                        'assertion' => 0,
                        'tag' => 'en',
                        'value' => ['type' => 'stringable', 'value' => 'de'],
                        'expected' => 'de',
                    ],
                    [
                        'assertion' => 1,
                        'tag' => 'en-US',
                        'value' => ['type' => 'stringable', 'value' => 'de'],
                        'expected' => 'de-US',
                    ],
                    [
                        'assertion' => 2,
                        'tag' => 'en-els',
                        'value' => ['type' => 'stringable', 'value' => 'de'],
                        'expected' => Midnight\Intl\Exception\RangeError::class,
                    ],
                ],
                array_merge(...array_map(
                    static fn(array $value): array => array_map(
                        static fn(int $assertion, string $tag): array => [
                            'assertion' => $assertion,
                            'tag' => $tag,
                            'value' => $value,
                            'expected' => Midnight\Intl\Exception\RangeError::class,
                        ],
                        [3, 4, 5],
                        ['en', 'en-US', 'en-els'],
                    ),
                    [
                        ['type' => 'null'],
                        ['type' => 'string', 'value' => 'zh-cmn'],
                        ['type' => 'string', 'value' => 'ZH-CMN'],
                        ['type' => 'string', 'value' => 'abcd'],
                    ],
                )),
            ),
        ),
        'test/intl402/Locale/constructor-options-region-invalid.js' => $mappedOptionPipeline(
            'region',
            'eb88f909f874da2e065480c8cc2e86b8d6b4555dc8d80812d5691dac9b855569',
            $invalidCases(array_map(static fn(string|int $value): array => [
                'type' => is_int($value) ? 'int' : 'string',
                'value' => $value,
            ], [
                '',
                'a',
                'abc',
                'a7',
                'notaregion',
                'SA-vaidika',
                'SA-a-asdf',
                'SA-x-private',
                'ary-Arab',
                'Latn-SA',
                'Latn-vaidika',
                'Latn-a-asdf',
                'Latn-x-private',
                7,
            ])),
        ),
        'test/intl402/Locale/constructor-options-region-valid.js' => $mappedOptionPipeline(
            'region',
            '1d138f46632f20db49f2e6b96a2ca66cfc814d35ea94d5e93e6d6c50e8059f2d',
            array_merge(...array_map(
                static fn(array $row): array => array_map(
                    static fn(int $assertion, string $tag, string $expected): array => [
                        'assertion' => $assertion,
                        'tag' => $tag,
                        'value' => $row[0],
                        'expected' => $expected,
                    ],
                    [0, 1, 2, 3],
                    ['en', 'en-US', 'en-u-ca-gregory', 'en-US-u-ca-gregory'],
                    $row[1],
                ),
                [
                    [['type' => 'undefined'], ['en', 'en-US', 'en-u-ca-gregory', 'en-US-u-ca-gregory']],
                    [
                        ['type' => 'string', 'value' => 'FR'],
                        ['en-FR', 'en-FR', 'en-FR-u-ca-gregory', 'en-FR-u-ca-gregory'],
                    ],
                    [
                        ['type' => 'string', 'value' => '554'],
                        ['en-NZ', 'en-NZ', 'en-NZ-u-ca-gregory', 'en-NZ-u-ca-gregory'],
                    ],
                    [['type' => 'int', 'value' => 554], ['en-NZ', 'en-NZ', 'en-NZ-u-ca-gregory', 'en-NZ-u-ca-gregory']],
                ],
            )),
        ),
        'test/intl402/Locale/constructor-options-script-invalid.js' => $mappedOptionPipeline(
            'script',
            'eaffbb2741f360729d4f732331525876c4eaa9736be43efe724b2953455e0412',
            $invalidCases(array_map(static fn(string|int $value): array => [
                'type' => is_int($value) ? 'int' : 'string',
                'value' => $value,
            ], [
                '',
                'a',
                'ab',
                'abc',
                'abc7',
                'notascript',
                'undefined',
                "Bal\u{0130}",
                "Bal\u{0131}",
                'ary-Arab',
                'Latn-SA',
                'Latn-vaidika',
                'Latn-a-asdf',
                'Latn-x-private',
                7,
            ])),
        ),
        'test/intl402/Locale/constructor-options-variants-invalid.js' => $mappedOptionPipeline(
            'variants',
            'b2c1b4589959a1c94ab97ab3049489289a649ef9324e021541250063654a991f',
            $invalidCases(array_map(static fn(string $value): array => ['type' => 'string', 'value' => $value], [
                '',
                'a',
                '1',
                'ab',
                '2x',
                'abc',
                '3xy',
                'abcd',
                'abcdefghi',
                'GB-scouse',
                'fonipa-fonipa',
                'fonipa-valencia-Fonipa',
                '-',
                '-spanglis',
                'spanglis-',
                '-spanglis-oxendict',
                'spanglis-oxendict-',
                'spanglis--oxendict',
            ])),
        ),
        'test/intl402/Locale/constructor-options-variants-valid.js' => $mappedOptionPipeline(
            'variants',
            '3f636ca71f4f6a75e8f818b341707ac17ead7ba0e432c3a12877e3dd1cbdaf07',
            array_merge(...array_map(
                static fn(array $row): array => array_map(
                    static fn(int $assertion, string $tag, string $expected): array => [
                        'assertion' => $assertion,
                        'tag' => $tag,
                        'value' => $row[1],
                        'expected' => $expected,
                    ],
                    [0, 1, 2, 3],
                    [$row[0], $row[0] . '-fonipa', $row[0] . '-u-ca-gregory', $row[0] . '-fonipa-u-ca-gregory'],
                    $row[2],
                ),
                [
                    ['en', ['type' => 'undefined'], ['en', 'en-fonipa', 'en-u-ca-gregory', 'en-fonipa-u-ca-gregory']],
                    [
                        'en',
                        ['type' => 'string', 'value' => 'spanglis'],
                        ['en-spanglis', 'en-spanglis', 'en-spanglis-u-ca-gregory', 'en-spanglis-u-ca-gregory'],
                    ],
                    [
                        'xx',
                        ['type' => 'string', 'value' => '1xyz'],
                        ['xx-1xyz', 'xx-1xyz', 'xx-1xyz-u-ca-gregory', 'xx-1xyz-u-ca-gregory'],
                    ],
                    [
                        'xx',
                        ['type' => 'string', 'value' => '1234'],
                        ['xx-1234', 'xx-1234', 'xx-1234-u-ca-gregory', 'xx-1234-u-ca-gregory'],
                    ],
                    [
                        'xx',
                        ['type' => 'string', 'value' => 'abcde'],
                        ['xx-abcde', 'xx-abcde', 'xx-abcde-u-ca-gregory', 'xx-abcde-u-ca-gregory'],
                    ],
                    [
                        'xx',
                        ['type' => 'string', 'value' => '12345678'],
                        ['xx-12345678', 'xx-12345678', 'xx-12345678-u-ca-gregory', 'xx-12345678-u-ca-gregory'],
                    ],
                    [
                        'xx',
                        ['type' => 'string', 'value' => '1xyz-1234-abcde-12345678'],
                        [
                            'xx-1234-12345678-1xyz-abcde',
                            'xx-1234-12345678-1xyz-abcde',
                            'xx-1234-12345678-1xyz-abcde-u-ca-gregory',
                            'xx-1234-12345678-1xyz-abcde-u-ca-gregory',
                        ],
                    ],
                    [
                        'en',
                        ['type' => 'string', 'value' => 'spanglis-oxendict'],
                        [
                            'en-oxendict-spanglis',
                            'en-oxendict-spanglis',
                            'en-oxendict-spanglis-u-ca-gregory',
                            'en-oxendict-spanglis-u-ca-gregory',
                        ],
                    ],
                ],
            )),
        ),
        'test/intl402/Locale/constructor-apply-options-canonicalizes-twice.js' => $mappedOptionPipeline(
            'language',
            'aa542114d28bfdc89fe10ac51cde78837a8392ef1d52ecf47cbfcd9a5dec2fbb',
            [
                [
                    'assertion' => 0,
                    'tag' => 'und-Armn-SU',
                    'value' => ['type' => 'string', 'value' => 'ru'],
                    'expected' => 'ru-Armn-AM',
                ],
            ],
        ),
        'test/intl402/Locale/constructor-getter-order.js' => $catalog->optionObservation(
            'order',
            [],
            'ee7935bd44614b4c2095766bfc328b02c28264e04ac036bc6d93223db32b8044',
        ),
        'test/intl402/Locale/constructor-options-throwing-getters.js' => $catalog->optionObservation(
            'throws',
            [
                'language',
                'script',
                'region',
                'variants',
                'calendar',
                'collation',
                'firstDayOfWeek',
                'hourCycle',
                'caseFirst',
                'numeric',
                'numberingSystem',
            ],
            'c2b93ea685d76e9d43a1dc4339b298323d1cf8809e1ddf66e2b9e20d07eab882',
        ),
        'test/intl402/Locale/constructor-locale-object.js' => $catalog->localeObject(
            'c4c6ac019b341d0660e8f3869943e39312b45e21e36f178abef600505aef243d',
        ),
    ];
};
