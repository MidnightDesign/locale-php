<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-language-grandfathered.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsLanguageGrandfatheredTest extends TestCase
{
    /** @return array<string, array{string, string, string, array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}, string, string}> */
    public static function cases(): array
    {
        return array(
            'case-1-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-language-grandfathered.js:L21:C1:assert.throws',
                1 => 'nb',
                2 => 'language',
                3 => array(
                    'type' => 'string',
                    'value' => 'no-bok',
                ),
                4 => 'associative_array',
                5 => 'Midnight\\Intl\\Exception\\RangeError',
            ),
            'case-1-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-language-grandfathered.js:L21:C1:assert.throws',
                1 => 'nb',
                2 => 'language',
                3 => array(
                    'type' => 'string',
                    'value' => 'no-bok',
                ),
                4 => 'plain_object',
                5 => 'Midnight\\Intl\\Exception\\RangeError',
            ),
            'case-2-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-language-grandfathered.js:L27:C1:assert.throws',
                1 => 'nb',
                2 => 'language',
                3 => array(
                    'type' => 'string',
                    'value' => 'no-bok',
                ),
                4 => 'associative_array',
                5 => 'Midnight\\Intl\\Exception\\RangeError',
            ),
            'case-2-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-language-grandfathered.js:L27:C1:assert.throws',
                1 => 'nb',
                2 => 'language',
                3 => array(
                    'type' => 'string',
                    'value' => 'no-bok',
                ),
                4 => 'plain_object',
                5 => 'Midnight\\Intl\\Exception\\RangeError',
            ),
        );
    }

    /** @param array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int} $value */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(
        string $assertionId,
        string $tag,
        string $optionName,
        array $value,
        string $representation,
        string $expected,
    ): void {
        $result = $expected === RangeError::class
            ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
            : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected);
        self::assertSame('passing', $result['status'], $assertionId . ': ' . ($result['failure'] ?? 'unknown failure'));
    }
}
