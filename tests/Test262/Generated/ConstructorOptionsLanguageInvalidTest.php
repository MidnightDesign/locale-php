<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-language-invalid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsLanguageInvalidTest extends TestCase
{
    /** @return array<string, array{string, string, string, array<string, mixed>, string, string|bool, ?string}> */
    public static function cases(): array
    {
        return array(
  'case-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => '',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => '',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'a',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'a',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'ab7',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'ab7',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-4-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'notalanguage',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-4-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'notalanguage',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-5-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'undefined',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-5-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'undefined',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-6-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'root',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-6-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'root',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-7-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-Latn',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-7-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-Latn',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-8-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-FR',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-8-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-FR',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-9-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sa-vaidika',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-9-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sa-vaidika',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-10-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-a-asdf',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-10-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-a-asdf',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-11-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-x-private',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-11-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fr-x-private',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-12-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'i-klingon',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-12-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'i-klingon',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-13-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'zh-min',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-13-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'zh-min',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-14-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'zh-min-nan',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-14-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'zh-min-nan',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-15-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcd-US',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-15-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcd-US',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-16-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde-US',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-16-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcde-US',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-17-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcdef-US',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-17-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcdef-US',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-18-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcdefg-US',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-18-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcdefg-US',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-19-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcdefgh-US',
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-19-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'string',
      'value' => 'abcdefgh-US',
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-20-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'int',
      'value' => 7,
    ),
    4 => 'associative_array',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
  ),
  'case-20-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-language-invalid.js:L71:C3:assert.throws',
    1 => 'en',
    2 => 'language',
    3 =>
    array(
      'type' => 'int',
      'value' => 7,
    ),
    4 => 'plain_object',
    5 => 'Midnight\\Intl\\Exception\\RangeError',
    6 => null,
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
