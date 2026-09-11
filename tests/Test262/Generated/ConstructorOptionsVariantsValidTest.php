<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-variants-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsVariantsValidTest extends TestCase
{
    /** @return array<string, array{string, string, string, array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}, string, string}> */
    public static function cases(): array
    {
        return array(
  'case-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'associative_array',
    5 => 'en',
  ),
  'case-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'plain_object',
    5 => 'en',
  ),
  'case-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'en-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'associative_array',
    5 => 'en-fonipa',
  ),
  'case-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'en-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'plain_object',
    5 => 'en-fonipa',
  ),
  'case-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'en-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'associative_array',
    5 => 'en-u-ca-gregory',
  ),
  'case-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'en-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'plain_object',
    5 => 'en-u-ca-gregory',
  ),
  'case-4-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'en-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'associative_array',
    5 => 'en-fonipa-u-ca-gregory',
  ),
  'case-4-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'en-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'undefined',
    ),
    4 => 'plain_object',
    5 => 'en-fonipa-u-ca-gregory',
  ),
  'case-5-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'associative_array',
    5 => 'en-spanglis',
  ),
  'case-5-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'plain_object',
    5 => 'en-spanglis',
  ),
  'case-6-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'en-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'associative_array',
    5 => 'en-spanglis',
  ),
  'case-6-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'en-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'plain_object',
    5 => 'en-spanglis',
  ),
  'case-7-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'en-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'associative_array',
    5 => 'en-spanglis-u-ca-gregory',
  ),
  'case-7-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'en-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'plain_object',
    5 => 'en-spanglis-u-ca-gregory',
  ),
  'case-8-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'en-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'associative_array',
    5 => 'en-spanglis-u-ca-gregory',
  ),
  'case-8-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'en-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis',
    ),
    4 => 'plain_object',
    5 => 'en-spanglis-u-ca-gregory',
  ),
  'case-9-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'associative_array',
    5 => 'xx-1xyz',
  ),
  'case-9-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'plain_object',
    5 => 'xx-1xyz',
  ),
  'case-10-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'associative_array',
    5 => 'xx-1xyz',
  ),
  'case-10-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'plain_object',
    5 => 'xx-1xyz',
  ),
  'case-11-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'associative_array',
    5 => 'xx-1xyz-u-ca-gregory',
  ),
  'case-11-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'plain_object',
    5 => 'xx-1xyz-u-ca-gregory',
  ),
  'case-12-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'associative_array',
    5 => 'xx-1xyz-u-ca-gregory',
  ),
  'case-12-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz',
    ),
    4 => 'plain_object',
    5 => 'xx-1xyz-u-ca-gregory',
  ),
  'case-13-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'associative_array',
    5 => 'xx-1234',
  ),
  'case-13-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'plain_object',
    5 => 'xx-1234',
  ),
  'case-14-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'associative_array',
    5 => 'xx-1234',
  ),
  'case-14-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'plain_object',
    5 => 'xx-1234',
  ),
  'case-15-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'associative_array',
    5 => 'xx-1234-u-ca-gregory',
  ),
  'case-15-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'plain_object',
    5 => 'xx-1234-u-ca-gregory',
  ),
  'case-16-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'associative_array',
    5 => 'xx-1234-u-ca-gregory',
  ),
  'case-16-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1234',
    ),
    4 => 'plain_object',
    5 => 'xx-1234-u-ca-gregory',
  ),
  'case-17-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'associative_array',
    5 => 'xx-abcde',
  ),
  'case-17-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'plain_object',
    5 => 'xx-abcde',
  ),
  'case-18-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'associative_array',
    5 => 'xx-abcde',
  ),
  'case-18-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'plain_object',
    5 => 'xx-abcde',
  ),
  'case-19-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'associative_array',
    5 => 'xx-abcde-u-ca-gregory',
  ),
  'case-19-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'plain_object',
    5 => 'xx-abcde-u-ca-gregory',
  ),
  'case-20-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'associative_array',
    5 => 'xx-abcde-u-ca-gregory',
  ),
  'case-20-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde',
    ),
    4 => 'plain_object',
    5 => 'xx-abcde-u-ca-gregory',
  ),
  'case-21-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-12345678',
  ),
  'case-21-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-12345678',
  ),
  'case-22-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-12345678',
  ),
  'case-22-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-12345678',
  ),
  'case-23-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-12345678-u-ca-gregory',
  ),
  'case-23-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-12345678-u-ca-gregory',
  ),
  'case-24-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-12345678-u-ca-gregory',
  ),
  'case-24-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-12345678-u-ca-gregory',
  ),
  'case-25-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-1234-12345678-1xyz-abcde',
  ),
  'case-25-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'xx',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-1234-12345678-1xyz-abcde',
  ),
  'case-26-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-1234-12345678-1xyz-abcde',
  ),
  'case-26-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'xx-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-1234-12345678-1xyz-abcde',
  ),
  'case-27-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-1234-12345678-1xyz-abcde-u-ca-gregory',
  ),
  'case-27-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'xx-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-1234-12345678-1xyz-abcde-u-ca-gregory',
  ),
  'case-28-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'associative_array',
    5 => 'xx-1234-12345678-1xyz-abcde-u-ca-gregory',
  ),
  'case-28-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'xx-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => '1xyz-1234-abcde-12345678',
    ),
    4 => 'plain_object',
    5 => 'xx-1234-12345678-1xyz-abcde-u-ca-gregory',
  ),
  'case-29-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'associative_array',
    5 => 'en-oxendict-spanglis',
  ),
  'case-29-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'plain_object',
    5 => 'en-oxendict-spanglis',
  ),
  'case-30-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'en-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'associative_array',
    5 => 'en-oxendict-spanglis',
  ),
  'case-30-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L48:C3:assert.sameValue',
    1 => 'en-fonipa',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'plain_object',
    5 => 'en-oxendict-spanglis',
  ),
  'case-31-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'en-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'associative_array',
    5 => 'en-oxendict-spanglis-u-ca-gregory',
  ),
  'case-31-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L53:C3:assert.sameValue',
    1 => 'en-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'plain_object',
    5 => 'en-oxendict-spanglis-u-ca-gregory',
  ),
  'case-32-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'en-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'associative_array',
    5 => 'en-oxendict-spanglis-u-ca-gregory',
  ),
  'case-32-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-variants-valid.js:L58:C3:assert.sameValue',
    1 => 'en-fonipa-u-ca-gregory',
    2 => 'variants',
    3 =>
    array(
      'type' => 'string',
      'value' => 'spanglis-oxendict',
    ),
    4 => 'plain_object',
    5 => 'en-oxendict-spanglis-u-ca-gregory',
  ),
);
    }

    /** @param array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int} $value */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(string $assertionId, string $tag, string $optionName, array $value, string $representation, string $expected): void
    {
        $result = $expected === RangeError::class
            ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
            : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected);
        self::assertSame('passing', $result['status'], $assertionId.': '.($result['failure'] ?? 'unknown failure'));
    }
}
