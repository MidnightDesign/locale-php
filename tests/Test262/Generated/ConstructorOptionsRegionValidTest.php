<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-region-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsRegionValidTest extends TestCase
{
    /** @return array<string, array{string, string, string, array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}, string, string}> */
    public static function cases(): array
    {
        return array(
            'case-1-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'associative_array',
                5 => 'en',
            ),
            'case-1-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'plain_object',
                5 => 'en',
            ),
            'case-2-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'associative_array',
                5 => 'en-US',
            ),
            'case-2-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'plain_object',
                5 => 'en-US',
            ),
            'case-3-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'associative_array',
                5 => 'en-u-ca-gregory',
            ),
            'case-3-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'plain_object',
                5 => 'en-u-ca-gregory',
            ),
            'case-4-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'associative_array',
                5 => 'en-US-u-ca-gregory',
            ),
            'case-4-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'undefined',
                ),
                4 => 'plain_object',
                5 => 'en-US-u-ca-gregory',
            ),
            'case-5-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'associative_array',
                5 => 'en-FR',
            ),
            'case-5-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'plain_object',
                5 => 'en-FR',
            ),
            'case-6-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'associative_array',
                5 => 'en-FR',
            ),
            'case-6-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'plain_object',
                5 => 'en-FR',
            ),
            'case-7-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'associative_array',
                5 => 'en-FR-u-ca-gregory',
            ),
            'case-7-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'plain_object',
                5 => 'en-FR-u-ca-gregory',
            ),
            'case-8-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'associative_array',
                5 => 'en-FR-u-ca-gregory',
            ),
            'case-8-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => 'FR',
                ),
                4 => 'plain_object',
                5 => 'en-FR-u-ca-gregory',
            ),
            'case-9-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'associative_array',
                5 => 'en-NZ',
            ),
            'case-9-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'plain_object',
                5 => 'en-NZ',
            ),
            'case-10-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'associative_array',
                5 => 'en-NZ',
            ),
            'case-10-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'plain_object',
                5 => 'en-NZ',
            ),
            'case-11-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'associative_array',
                5 => 'en-NZ-u-ca-gregory',
            ),
            'case-11-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'plain_object',
                5 => 'en-NZ-u-ca-gregory',
            ),
            'case-12-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'associative_array',
                5 => 'en-NZ-u-ca-gregory',
            ),
            'case-12-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'string',
                    'value' => '554',
                ),
                4 => 'plain_object',
                5 => 'en-NZ-u-ca-gregory',
            ),
            'case-13-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'associative_array',
                5 => 'en-NZ',
            ),
            'case-13-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L41:C3:assert.sameValue',
                1 => 'en',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'plain_object',
                5 => 'en-NZ',
            ),
            'case-14-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'associative_array',
                5 => 'en-NZ',
            ),
            'case-14-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L48:C3:assert.sameValue',
                1 => 'en-US',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'plain_object',
                5 => 'en-NZ',
            ),
            'case-15-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'associative_array',
                5 => 'en-NZ-u-ca-gregory',
            ),
            'case-15-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L55:C3:assert.sameValue',
                1 => 'en-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'plain_object',
                5 => 'en-NZ-u-ca-gregory',
            ),
            'case-16-associative_array' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'associative_array',
                5 => 'en-NZ-u-ca-gregory',
            ),
            'case-16-plain_object' => array(
                0 => 'test/intl402/Locale/constructor-options-region-valid.js:L62:C3:assert.sameValue',
                1 => 'en-US-u-ca-gregory',
                2 => 'region',
                3 => array(
                    'type' => 'int',
                    'value' => 554,
                ),
                4 => 'plain_object',
                5 => 'en-NZ-u-ca-gregory',
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
