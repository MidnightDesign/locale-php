<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-script-valid-undefined.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsScriptValidUndefinedTest extends TestCase
{
    /** @return array<string, array{string, string, string, string, string}> */
    public static function cases(): array
    {
        return array(
  'assertion-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid-undefined.js:L31:C1:assert.sameValue',
    1 => 'en',
    2 => 'script',
    3 => 'associative_array',
    4 => 'en',
  ),
  'assertion-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid-undefined.js:L31:C1:assert.sameValue',
    1 => 'en',
    2 => 'script',
    3 => 'plain_object',
    4 => 'en',
  ),
  'assertion-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid-undefined.js:L37:C1:assert.sameValue',
    1 => 'en-DK',
    2 => 'script',
    3 => 'associative_array',
    4 => 'en-DK',
  ),
  'assertion-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid-undefined.js:L37:C1:assert.sameValue',
    1 => 'en-DK',
    2 => 'script',
    3 => 'plain_object',
    4 => 'en-DK',
  ),
  'assertion-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid-undefined.js:L43:C1:assert.sameValue',
    1 => 'en-Cyrl',
    2 => 'script',
    3 => 'associative_array',
    4 => 'en-Cyrl',
  ),
  'assertion-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-script-valid-undefined.js:L43:C1:assert.sameValue',
    1 => 'en-Cyrl',
    2 => 'script',
    3 => 'plain_object',
    4 => 'en-Cyrl',
  ),
);
    }

    #[DataProvider('cases')]
    public function testTranslatedAssertions(
        string $assertionId,
        string $tag,
        string $optionName,
        string $representation,
        string $expected,
    ): void {
        $result = $expected === RangeError::class
            ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, ['type' => 'undefined'], $representation)
            : ConstructorOptionAssertion::evaluate($tag, $optionName, ['type' => 'undefined'], $representation, $expected);

        self::assertSame('passing', $result['status'], $assertionId.': '.($result['failure'] ?? 'unknown failure'));
    }
}
