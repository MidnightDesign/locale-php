<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/getters.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GettersTest extends TestCase
{
    /** @return array<string, array{string, string, ?array<string, mixed>, string, string, string|bool|null}> */
    public static function cases(): array
    {
        return array(
  'case-1-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L105:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'toString',
    5 => 'de-Latn-DE-1996-fonipa-u-ca-gregory-co-phonebk-hc-h23-kf-kn-false-nu-latn',
  ),
  'case-2-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L106:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'baseName',
    5 => 'de-Latn-DE-1996-fonipa',
  ),
  'case-3-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L107:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'language',
    5 => 'de',
  ),
  'case-4-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L108:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'script',
    5 => 'Latn',
  ),
  'case-5-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L109:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'region',
    5 => 'DE',
  ),
  'case-6-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L110:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'variants',
    5 => '1996-fonipa',
  ),
  'case-7-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L111:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'calendar',
    5 => 'gregory',
  ),
  'case-8-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L112:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'collation',
    5 => 'phonebk',
  ),
  'case-9-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L113:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'hourCycle',
    5 => 'h23',
  ),
  'case-10-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L115:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'caseFirst',
    5 => '',
  ),
  'case-11-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L118:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'numeric',
    5 => false,
  ),
  'case-12-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L120:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 => null,
    3 => 'direct',
    4 => 'numberingSystem',
    5 => 'latn',
  ),
  'case-13-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L137:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'toString',
    5 => 'ja-Jpan-JP-hepburn-u-ca-japanese-co-search-hc-h24-kf-false-kn-nu-jpanfin',
  ),
  'case-14-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L137:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'toString',
    5 => 'ja-Jpan-JP-hepburn-u-ca-japanese-co-search-hc-h24-kf-false-kn-nu-jpanfin',
  ),
  'case-15-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L138:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'baseName',
    5 => 'ja-Jpan-JP-hepburn',
  ),
  'case-16-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L138:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'baseName',
    5 => 'ja-Jpan-JP-hepburn',
  ),
  'case-17-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L139:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'language',
    5 => 'ja',
  ),
  'case-18-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L139:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'language',
    5 => 'ja',
  ),
  'case-19-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L140:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'script',
    5 => 'Jpan',
  ),
  'case-20-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L140:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'script',
    5 => 'Jpan',
  ),
  'case-21-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L141:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'region',
    5 => 'JP',
  ),
  'case-22-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L141:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'region',
    5 => 'JP',
  ),
  'case-23-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L142:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'variants',
    5 => 'hepburn',
  ),
  'case-24-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L142:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'variants',
    5 => 'hepburn',
  ),
  'case-25-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L143:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'calendar',
    5 => 'japanese',
  ),
  'case-26-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L143:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'calendar',
    5 => 'japanese',
  ),
  'case-27-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L144:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'collation',
    5 => 'search',
  ),
  'case-28-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L144:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'collation',
    5 => 'search',
  ),
  'case-29-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L145:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'hourCycle',
    5 => 'h24',
  ),
  'case-30-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L145:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'hourCycle',
    5 => 'h24',
  ),
  'case-31-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L147:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'caseFirst',
    5 => 'false',
  ),
  'case-32-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L147:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'caseFirst',
    5 => 'false',
  ),
  'case-33-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L150:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'numeric',
    5 => true,
  ),
  'case-34-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L150:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'numeric',
    5 => true,
  ),
  'case-35-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L152:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'associative_array',
    4 => 'numberingSystem',
    5 => 'jpanfin',
  ),
  'case-36-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L152:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
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
    3 => 'plain_object',
    4 => 'numberingSystem',
    5 => 'jpanfin',
  ),
  'case-37-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L163:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'toString',
    5 => 'fr-Latn-CA-1996-fonipa-u-ca-gregory-co-standard-hc-h11-kf-kn-false-nu-latn',
  ),
  'case-38-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L163:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'toString',
    5 => 'fr-Latn-CA-1996-fonipa-u-ca-gregory-co-standard-hc-h11-kf-kn-false-nu-latn',
  ),
  'case-39-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L164:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'baseName',
    5 => 'fr-Latn-CA-1996-fonipa',
  ),
  'case-40-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L164:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'baseName',
    5 => 'fr-Latn-CA-1996-fonipa',
  ),
  'case-41-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L165:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'language',
    5 => 'fr',
  ),
  'case-42-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L165:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'language',
    5 => 'fr',
  ),
  'case-43-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L166:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'script',
    5 => 'Latn',
  ),
  'case-44-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L166:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'script',
    5 => 'Latn',
  ),
  'case-45-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L167:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'region',
    5 => 'CA',
  ),
  'case-46-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L167:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'region',
    5 => 'CA',
  ),
  'case-47-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L168:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'variants',
    5 => '1996-fonipa',
  ),
  'case-48-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L168:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'variants',
    5 => '1996-fonipa',
  ),
  'case-49-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L169:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'calendar',
    5 => 'gregory',
  ),
  'case-50-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L169:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'calendar',
    5 => 'gregory',
  ),
  'case-51-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L170:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'collation',
    5 => 'standard',
  ),
  'case-52-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L170:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'collation',
    5 => 'standard',
  ),
  'case-53-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L171:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'hourCycle',
    5 => 'h11',
  ),
  'case-54-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L171:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'hourCycle',
    5 => 'h11',
  ),
  'case-55-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L173:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'caseFirst',
    5 => '',
  ),
  'case-56-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L173:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'caseFirst',
    5 => '',
  ),
  'case-57-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L176:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'numeric',
    5 => false,
  ),
  'case-58-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L176:C5:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'numeric',
    5 => false,
  ),
  'case-59-associative_array' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L178:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'associative_array',
    4 => 'numberingSystem',
    5 => 'latn',
  ),
  'case-60-plain_object' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L178:C1:assert.sameValue',
    1 => 'de-latn-de-fonipa-1996-u-ca-gregory-co-phonebk-hc-h23-kf-true-kn-false-nu-latn',
    2 =>
    array(
      'language' => 'fr',
      'region' => 'ca',
      'collation' => 'standard',
      'hourCycle' => 'h11',
    ),
    3 => 'plain_object',
    4 => 'numberingSystem',
    5 => 'latn',
  ),
  'case-61-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L183:C1:assert.sameValue',
    1 => 'und',
    2 => null,
    3 => 'direct',
    4 => 'toString',
    5 => 'und',
  ),
  'case-62-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L184:C1:assert.sameValue',
    1 => 'und',
    2 => null,
    3 => 'direct',
    4 => 'baseName',
    5 => 'und',
  ),
  'case-63-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L185:C1:assert.sameValue',
    1 => 'und',
    2 => null,
    3 => 'direct',
    4 => 'language',
    5 => 'und',
  ),
  'case-64-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L186:C1:assert.sameValue',
    1 => 'und',
    2 => null,
    3 => 'direct',
    4 => 'script',
    5 => null,
  ),
  'case-65-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L187:C1:assert.sameValue',
    1 => 'und',
    2 => null,
    3 => 'direct',
    4 => 'region',
    5 => null,
  ),
  'case-66-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L188:C1:assert.sameValue',
    1 => 'und',
    2 => null,
    3 => 'direct',
    4 => 'variants',
    5 => null,
  ),
  'case-67-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L192:C1:assert.sameValue',
    1 => 'und-US-u-co-emoji',
    2 => null,
    3 => 'direct',
    4 => 'toString',
    5 => 'und-US-u-co-emoji',
  ),
  'case-68-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L193:C1:assert.sameValue',
    1 => 'und-US-u-co-emoji',
    2 => null,
    3 => 'direct',
    4 => 'baseName',
    5 => 'und-US',
  ),
  'case-69-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L194:C1:assert.sameValue',
    1 => 'und-US-u-co-emoji',
    2 => null,
    3 => 'direct',
    4 => 'language',
    5 => 'und',
  ),
  'case-70-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L195:C1:assert.sameValue',
    1 => 'und-US-u-co-emoji',
    2 => null,
    3 => 'direct',
    4 => 'script',
    5 => null,
  ),
  'case-71-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L196:C1:assert.sameValue',
    1 => 'und-US-u-co-emoji',
    2 => null,
    3 => 'direct',
    4 => 'region',
    5 => 'US',
  ),
  'case-72-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L197:C1:assert.sameValue',
    1 => 'und-US-u-co-emoji',
    2 => null,
    3 => 'direct',
    4 => 'variants',
    5 => null,
  ),
  'case-73-direct' =>
  array(
    0 => 'test/intl402/Locale/getters.js:L199:C5:assert.sameValue',
    1 => 'und-US-u-co-emoji',
    2 => null,
    3 => 'direct',
    4 => 'collation',
    5 => 'emoji',
  ),
);
    }

    /** @param array<string, mixed>|null $options */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(string $assertionId, string $tag, ?array $options, string $representation, string $property, string|bool|null $expected): void
    {
        $locale = match ($representation) {
            'direct' => new Locale($tag),
            'associative_array' => new Locale($tag, $options),
            'plain_object' => new Locale($tag, (object) $options),
            default => throw new \InvalidArgumentException('Unsupported representation.'),
        };
        $actual = $property === 'toString' ? $locale->toString() : $locale->{$property};

        self::assertSame($expected, $actual, $assertionId);
    }
}
