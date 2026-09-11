<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/getters.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
use PHPUnit\Framework\Assert;

foreach (array(
    'scenario-1-direct' => array(
        0 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/getters.js:L105:C1:assert.sameValue',
                1 => 'toString',
                2 => 'de-Latn-DE-1996-fonipa-u-ca-gregory-co-phonebk-hc-h23-kf-kn-false-nu-latn',
            ),
            1 => array(
                0 => 'test/intl402/Locale/getters.js:L106:C1:assert.sameValue',
                1 => 'baseName',
                2 => 'de-Latn-DE-1996-fonipa',
            ),
            2 => array(
                0 => 'test/intl402/Locale/getters.js:L107:C1:assert.sameValue',
                1 => 'language',
                2 => 'de',
            ),
            3 => array(
                0 => 'test/intl402/Locale/getters.js:L108:C1:assert.sameValue',
                1 => 'script',
                2 => 'Latn',
            ),
            4 => array(
                0 => 'test/intl402/Locale/getters.js:L109:C1:assert.sameValue',
                1 => 'region',
                2 => 'DE',
            ),
            5 => array(
                0 => 'test/intl402/Locale/getters.js:L110:C1:assert.sameValue',
                1 => 'variants',
                2 => '1996-fonipa',
            ),
            6 => array(
                0 => 'test/intl402/Locale/getters.js:L111:C1:assert.sameValue',
                1 => 'calendar',
                2 => 'gregory',
            ),
            7 => array(
                0 => 'test/intl402/Locale/getters.js:L112:C1:assert.sameValue',
                1 => 'collation',
                2 => 'phonebk',
            ),
            8 => array(
                0 => 'test/intl402/Locale/getters.js:L113:C1:assert.sameValue',
                1 => 'hourCycle',
                2 => 'h23',
            ),
            9 => array(
                0 => 'test/intl402/Locale/getters.js:L115:C5:assert.sameValue',
                1 => 'caseFirst',
                2 => '',
            ),
            10 => array(
                0 => 'test/intl402/Locale/getters.js:L118:C5:assert.sameValue',
                1 => 'numeric',
                2 => false,
            ),
            11 => array(
                0 => 'test/intl402/Locale/getters.js:L120:C1:assert.sameValue',
                1 => 'numberingSystem',
                2 => 'latn',
            ),
        ),
    ),
    'scenario-2-associative_array' => array(
        0 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
        1 => array(
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
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/getters.js:L137:C1:assert.sameValue',
                1 => 'toString',
                2 => 'ja-Jpan-JP-hepburn-u-ca-japanese-co-search-hc-h24-kf-false-kn-nu-jpanfin',
            ),
            1 => array(
                0 => 'test/intl402/Locale/getters.js:L138:C1:assert.sameValue',
                1 => 'baseName',
                2 => 'ja-Jpan-JP-hepburn',
            ),
            2 => array(
                0 => 'test/intl402/Locale/getters.js:L139:C1:assert.sameValue',
                1 => 'language',
                2 => 'ja',
            ),
            3 => array(
                0 => 'test/intl402/Locale/getters.js:L140:C1:assert.sameValue',
                1 => 'script',
                2 => 'Jpan',
            ),
            4 => array(
                0 => 'test/intl402/Locale/getters.js:L141:C1:assert.sameValue',
                1 => 'region',
                2 => 'JP',
            ),
            5 => array(
                0 => 'test/intl402/Locale/getters.js:L142:C1:assert.sameValue',
                1 => 'variants',
                2 => 'hepburn',
            ),
            6 => array(
                0 => 'test/intl402/Locale/getters.js:L143:C1:assert.sameValue',
                1 => 'calendar',
                2 => 'japanese',
            ),
            7 => array(
                0 => 'test/intl402/Locale/getters.js:L144:C1:assert.sameValue',
                1 => 'collation',
                2 => 'search',
            ),
            8 => array(
                0 => 'test/intl402/Locale/getters.js:L145:C1:assert.sameValue',
                1 => 'hourCycle',
                2 => 'h24',
            ),
            9 => array(
                0 => 'test/intl402/Locale/getters.js:L147:C5:assert.sameValue',
                1 => 'caseFirst',
                2 => 'false',
            ),
            10 => array(
                0 => 'test/intl402/Locale/getters.js:L150:C5:assert.sameValue',
                1 => 'numeric',
                2 => true,
            ),
            11 => array(
                0 => 'test/intl402/Locale/getters.js:L152:C1:assert.sameValue',
                1 => 'numberingSystem',
                2 => 'jpanfin',
            ),
        ),
    ),
    'scenario-2-plain_object' => array(
        0 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
        1 => array(
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
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/getters.js:L137:C1:assert.sameValue',
                1 => 'toString',
                2 => 'ja-Jpan-JP-hepburn-u-ca-japanese-co-search-hc-h24-kf-false-kn-nu-jpanfin',
            ),
            1 => array(
                0 => 'test/intl402/Locale/getters.js:L138:C1:assert.sameValue',
                1 => 'baseName',
                2 => 'ja-Jpan-JP-hepburn',
            ),
            2 => array(
                0 => 'test/intl402/Locale/getters.js:L139:C1:assert.sameValue',
                1 => 'language',
                2 => 'ja',
            ),
            3 => array(
                0 => 'test/intl402/Locale/getters.js:L140:C1:assert.sameValue',
                1 => 'script',
                2 => 'Jpan',
            ),
            4 => array(
                0 => 'test/intl402/Locale/getters.js:L141:C1:assert.sameValue',
                1 => 'region',
                2 => 'JP',
            ),
            5 => array(
                0 => 'test/intl402/Locale/getters.js:L142:C1:assert.sameValue',
                1 => 'variants',
                2 => 'hepburn',
            ),
            6 => array(
                0 => 'test/intl402/Locale/getters.js:L143:C1:assert.sameValue',
                1 => 'calendar',
                2 => 'japanese',
            ),
            7 => array(
                0 => 'test/intl402/Locale/getters.js:L144:C1:assert.sameValue',
                1 => 'collation',
                2 => 'search',
            ),
            8 => array(
                0 => 'test/intl402/Locale/getters.js:L145:C1:assert.sameValue',
                1 => 'hourCycle',
                2 => 'h24',
            ),
            9 => array(
                0 => 'test/intl402/Locale/getters.js:L147:C5:assert.sameValue',
                1 => 'caseFirst',
                2 => 'false',
            ),
            10 => array(
                0 => 'test/intl402/Locale/getters.js:L150:C5:assert.sameValue',
                1 => 'numeric',
                2 => true,
            ),
            11 => array(
                0 => 'test/intl402/Locale/getters.js:L152:C1:assert.sameValue',
                1 => 'numberingSystem',
                2 => 'jpanfin',
            ),
        ),
    ),
    'scenario-3-associative_array' => array(
        0 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
        1 => array(
            'language' => 'fr',
            'region' => 'ca',
            'collation' => 'standard',
            'hourCycle' => 'h11',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/getters.js:L163:C1:assert.sameValue',
                1 => 'toString',
                2 => 'fr-Latn-CA-1996-fonipa-u-ca-gregory-co-standard-hc-h11-kf-kn-false-nu-latn',
            ),
            1 => array(
                0 => 'test/intl402/Locale/getters.js:L164:C1:assert.sameValue',
                1 => 'baseName',
                2 => 'fr-Latn-CA-1996-fonipa',
            ),
            2 => array(
                0 => 'test/intl402/Locale/getters.js:L165:C1:assert.sameValue',
                1 => 'language',
                2 => 'fr',
            ),
            3 => array(
                0 => 'test/intl402/Locale/getters.js:L166:C1:assert.sameValue',
                1 => 'script',
                2 => 'Latn',
            ),
            4 => array(
                0 => 'test/intl402/Locale/getters.js:L167:C1:assert.sameValue',
                1 => 'region',
                2 => 'CA',
            ),
            5 => array(
                0 => 'test/intl402/Locale/getters.js:L168:C1:assert.sameValue',
                1 => 'variants',
                2 => '1996-fonipa',
            ),
            6 => array(
                0 => 'test/intl402/Locale/getters.js:L169:C1:assert.sameValue',
                1 => 'calendar',
                2 => 'gregory',
            ),
            7 => array(
                0 => 'test/intl402/Locale/getters.js:L170:C1:assert.sameValue',
                1 => 'collation',
                2 => 'standard',
            ),
            8 => array(
                0 => 'test/intl402/Locale/getters.js:L171:C1:assert.sameValue',
                1 => 'hourCycle',
                2 => 'h11',
            ),
            9 => array(
                0 => 'test/intl402/Locale/getters.js:L173:C5:assert.sameValue',
                1 => 'caseFirst',
                2 => '',
            ),
            10 => array(
                0 => 'test/intl402/Locale/getters.js:L176:C5:assert.sameValue',
                1 => 'numeric',
                2 => false,
            ),
            11 => array(
                0 => 'test/intl402/Locale/getters.js:L178:C1:assert.sameValue',
                1 => 'numberingSystem',
                2 => 'latn',
            ),
        ),
    ),
    'scenario-3-plain_object' => array(
        0 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
        1 => array(
            'language' => 'fr',
            'region' => 'ca',
            'collation' => 'standard',
            'hourCycle' => 'h11',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/getters.js:L163:C1:assert.sameValue',
                1 => 'toString',
                2 => 'fr-Latn-CA-1996-fonipa-u-ca-gregory-co-standard-hc-h11-kf-kn-false-nu-latn',
            ),
            1 => array(
                0 => 'test/intl402/Locale/getters.js:L164:C1:assert.sameValue',
                1 => 'baseName',
                2 => 'fr-Latn-CA-1996-fonipa',
            ),
            2 => array(
                0 => 'test/intl402/Locale/getters.js:L165:C1:assert.sameValue',
                1 => 'language',
                2 => 'fr',
            ),
            3 => array(
                0 => 'test/intl402/Locale/getters.js:L166:C1:assert.sameValue',
                1 => 'script',
                2 => 'Latn',
            ),
            4 => array(
                0 => 'test/intl402/Locale/getters.js:L167:C1:assert.sameValue',
                1 => 'region',
                2 => 'CA',
            ),
            5 => array(
                0 => 'test/intl402/Locale/getters.js:L168:C1:assert.sameValue',
                1 => 'variants',
                2 => '1996-fonipa',
            ),
            6 => array(
                0 => 'test/intl402/Locale/getters.js:L169:C1:assert.sameValue',
                1 => 'calendar',
                2 => 'gregory',
            ),
            7 => array(
                0 => 'test/intl402/Locale/getters.js:L170:C1:assert.sameValue',
                1 => 'collation',
                2 => 'standard',
            ),
            8 => array(
                0 => 'test/intl402/Locale/getters.js:L171:C1:assert.sameValue',
                1 => 'hourCycle',
                2 => 'h11',
            ),
            9 => array(
                0 => 'test/intl402/Locale/getters.js:L173:C5:assert.sameValue',
                1 => 'caseFirst',
                2 => '',
            ),
            10 => array(
                0 => 'test/intl402/Locale/getters.js:L176:C5:assert.sameValue',
                1 => 'numeric',
                2 => false,
            ),
            11 => array(
                0 => 'test/intl402/Locale/getters.js:L178:C1:assert.sameValue',
                1 => 'numberingSystem',
                2 => 'latn',
            ),
        ),
    ),
    'scenario-4-direct' => array(
        0 => 'und',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/getters.js:L183:C1:assert.sameValue',
                1 => 'toString',
                2 => 'und',
            ),
            1 => array(
                0 => 'test/intl402/Locale/getters.js:L184:C1:assert.sameValue',
                1 => 'baseName',
                2 => 'und',
            ),
            2 => array(
                0 => 'test/intl402/Locale/getters.js:L185:C1:assert.sameValue',
                1 => 'language',
                2 => 'und',
            ),
            3 => array(
                0 => 'test/intl402/Locale/getters.js:L186:C1:assert.sameValue',
                1 => 'script',
                2 => null,
            ),
            4 => array(
                0 => 'test/intl402/Locale/getters.js:L187:C1:assert.sameValue',
                1 => 'region',
                2 => null,
            ),
            5 => array(
                0 => 'test/intl402/Locale/getters.js:L188:C1:assert.sameValue',
                1 => 'variants',
                2 => null,
            ),
        ),
    ),
    'scenario-5-direct' => array(
        0 => 'und-US-u-co-emoji',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/getters.js:L192:C1:assert.sameValue',
                1 => 'toString',
                2 => 'und-US-u-co-emoji',
            ),
            1 => array(
                0 => 'test/intl402/Locale/getters.js:L193:C1:assert.sameValue',
                1 => 'baseName',
                2 => 'und-US',
            ),
            2 => array(
                0 => 'test/intl402/Locale/getters.js:L194:C1:assert.sameValue',
                1 => 'language',
                2 => 'und',
            ),
            3 => array(
                0 => 'test/intl402/Locale/getters.js:L195:C1:assert.sameValue',
                1 => 'script',
                2 => null,
            ),
            4 => array(
                0 => 'test/intl402/Locale/getters.js:L196:C1:assert.sameValue',
                1 => 'region',
                2 => 'US',
            ),
            5 => array(
                0 => 'test/intl402/Locale/getters.js:L197:C1:assert.sameValue',
                1 => 'variants',
                2 => null,
            ),
            6 => array(
                0 => 'test/intl402/Locale/getters.js:L199:C5:assert.sameValue',
                1 => 'collation',
                2 => 'emoji',
            ),
        ),
    ),
) as [$tag, $options, $representation, $expectationTuples]) {
    $expectations = array_map(LocaleStateExpectation::fromTuple(...), $expectationTuples);
    $results = LocaleStateAssertion::evaluate($tag, $options, $representation, $expectations);
    foreach ($expectations as $index => $expectation) {
        $result = $results[$index];
        Assert::assertSame(
            'passing',
            $result['status'],
            $expectation->assertionId . ': ' . ($result['failure'] ?? 'unknown failure'),
        );
    }
}
