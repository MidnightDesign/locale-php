<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-casefirst-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsCaseFirstValidTest extends TestCase
{
    /** @return array<string, array{string, string, string, array<string, mixed>, string, string|bool, ?string}> */
    public static function cases(): array
    {
        return array(
  'case-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'upper',
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-upper',
    6 => null,
  ),
  'case-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'upper',
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-upper',
    6 => null,
  ),
  'case-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'upper',
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-upper',
    6 => null,
  ),
  'case-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'upper',
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-upper',
    6 => null,
  ),
  'case-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'lower',
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-lower',
    6 => null,
  ),
  'case-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'lower',
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-lower',
    6 => null,
  ),
  'case-4-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'lower',
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-lower',
    6 => null,
  ),
  'case-4-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'lower',
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-lower',
    6 => null,
  ),
  'case-5-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'false',
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-5-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'false',
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-6-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'false',
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-6-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'false',
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-7-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-7-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-8-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-8-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-9-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'primitive',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-9-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L48:C3:assert.sameValue',
    1 => 'en',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'primitive',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-10-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'primitive',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-10-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L55:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'primitive',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'en-u-kf-false',
    6 => null,
  ),
  'case-11-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'upper',
    ),
    4 => 'associative_array',
    5 => 'upper',
    6 => 'caseFirst',
  ),
  'case-11-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'upper',
    ),
    4 => 'plain_object',
    5 => 'upper',
    6 => 'caseFirst',
  ),
  'case-12-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'lower',
    ),
    4 => 'associative_array',
    5 => 'lower',
    6 => 'caseFirst',
  ),
  'case-12-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'lower',
    ),
    4 => 'plain_object',
    5 => 'lower',
    6 => 'caseFirst',
  ),
  'case-13-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'false',
    ),
    4 => 'associative_array',
    5 => 'false',
    6 => 'caseFirst',
  ),
  'case-13-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'string',
      'value' => 'false',
    ),
    4 => 'plain_object',
    5 => 'false',
    6 => 'caseFirst',
  ),
  'case-14-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'false',
    6 => 'caseFirst',
  ),
  'case-14-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'false',
    6 => 'caseFirst',
  ),
  'case-15-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'primitive',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'false',
    6 => 'caseFirst',
  ),
  'case-15-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-casefirst-valid.js:L61:C3:assert.sameValue',
    1 => 'en-u-kf-lower',
    2 => 'caseFirst',
    3 =>
    array(
      'type' => 'primitive',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'false',
    6 => 'caseFirst',
  ),
);
    }

    /** @param array<string, mixed> $value */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(string $assertionId, string $tag, string $optionName, array $value, string $representation, string|bool $expected, ?string $property): void
    {
        $result = $expected === RangeError::class
            ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
            : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected, $property);
        self::assertSame('passing', $result['status'], $assertionId.': '.($result['failure'] ?? 'unknown failure'));
    }
}
