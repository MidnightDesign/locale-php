<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-script-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsScriptValidTest extends TestCase
{
    /**
     * @return array<string, array{
     *     string,
     *     string,
     *     array{type: 'null'}|array{type: 'string'|'stringable', value: string},
     *     string,
     *     string
     * }>
     */
    public static function cases(): array
    {
        return array(
  'option-1-assertion-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'null',
    ),
    3 => 'associative_array',
    4 => 'en-Null',
  ),
  'option-1-assertion-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'null',
    ),
    3 => 'plain_object',
    4 => 'en-Null',
  ),
  'option-1-assertion-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'null',
    ),
    3 => 'associative_array',
    4 => 'en-Null-DK',
  ),
  'option-1-assertion-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'null',
    ),
    3 => 'plain_object',
    4 => 'en-Null-DK',
  ),
  'option-1-assertion-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'null',
    ),
    3 => 'associative_array',
    4 => 'en-Null',
  ),
  'option-1-assertion-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'null',
    ),
    3 => 'plain_object',
    4 => 'en-Null',
  ),
  'option-2-assertion-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bali',
    ),
    3 => 'associative_array',
    4 => 'en-Bali',
  ),
  'option-2-assertion-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bali',
    ),
    3 => 'plain_object',
    4 => 'en-Bali',
  ),
  'option-2-assertion-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bali',
    ),
    3 => 'associative_array',
    4 => 'en-Bali-DK',
  ),
  'option-2-assertion-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bali',
    ),
    3 => 'plain_object',
    4 => 'en-Bali-DK',
  ),
  'option-2-assertion-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bali',
    ),
    3 => 'associative_array',
    4 => 'en-Bali',
  ),
  'option-2-assertion-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bali',
    ),
    3 => 'plain_object',
    4 => 'en-Bali',
  ),
  'option-3-assertion-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'string',
      'value' => 'Bali',
    ),
    3 => 'associative_array',
    4 => 'en-Bali',
  ),
  'option-3-assertion-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'string',
      'value' => 'Bali',
    ),
    3 => 'plain_object',
    4 => 'en-Bali',
  ),
  'option-3-assertion-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'string',
      'value' => 'Bali',
    ),
    3 => 'associative_array',
    4 => 'en-Bali-DK',
  ),
  'option-3-assertion-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'string',
      'value' => 'Bali',
    ),
    3 => 'plain_object',
    4 => 'en-Bali-DK',
  ),
  'option-3-assertion-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'string',
      'value' => 'Bali',
    ),
    3 => 'associative_array',
    4 => 'en-Bali',
  ),
  'option-3-assertion-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'string',
      'value' => 'Bali',
    ),
    3 => 'plain_object',
    4 => 'en-Bali',
  ),
  'option-4-assertion-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bALI',
    ),
    3 => 'associative_array',
    4 => 'en-Bali',
  ),
  'option-4-assertion-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bALI',
    ),
    3 => 'plain_object',
    4 => 'en-Bali',
  ),
  'option-4-assertion-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bALI',
    ),
    3 => 'associative_array',
    4 => 'en-Bali-DK',
  ),
  'option-4-assertion-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bALI',
    ),
    3 => 'plain_object',
    4 => 'en-Bali-DK',
  ),
  'option-4-assertion-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bALI',
    ),
    3 => 'associative_array',
    4 => 'en-Bali',
  ),
  'option-4-assertion-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'string',
      'value' => 'bALI',
    ),
    3 => 'plain_object',
    4 => 'en-Bali',
  ),
  'option-5-assertion-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'stringable',
      'value' => 'Brai',
    ),
    3 => 'associative_array',
    4 => 'en-Brai',
  ),
  'option-5-assertion-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L43:C3:assert.sameValue',
    1 => 'en',
    2 =>
    array(
      'type' => 'stringable',
      'value' => 'Brai',
    ),
    3 => 'plain_object',
    4 => 'en-Brai',
  ),
  'option-5-assertion-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'stringable',
      'value' => 'Brai',
    ),
    3 => 'associative_array',
    4 => 'en-Brai-DK',
  ),
  'option-5-assertion-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L50:C3:assert.sameValue',
    1 => 'en-DK',
    2 =>
    array(
      'type' => 'stringable',
      'value' => 'Brai',
    ),
    3 => 'plain_object',
    4 => 'en-Brai-DK',
  ),
  'option-5-assertion-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'stringable',
      'value' => 'Brai',
    ),
    3 => 'associative_array',
    4 => 'en-Brai',
  ),
  'option-5-assertion-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid.js:L57:C3:assert.sameValue',
    1 => 'en-Cyrl',
    2 =>
    array(
      'type' => 'stringable',
      'value' => 'Brai',
    ),
    3 => 'plain_object',
    4 => 'en-Brai',
  ),
);
    }

    /** @param array{type: 'null'}|array{type: 'string'|'stringable', value: string} $optionValue */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(
        string $assertionId,
        string $tag,
        array $optionValue,
        string $representation,
        string $expected,
    ): void {
        $result = ConstructorOptionAssertion::evaluate(
            $tag,
            'script',
            $optionValue,
            $representation,
            $expected,
        );

        self::assertSame(
            'passing',
            $result['status'],
            $assertionId.': '.($result['failure'] ?? 'unknown failure'),
        );
    }
}
