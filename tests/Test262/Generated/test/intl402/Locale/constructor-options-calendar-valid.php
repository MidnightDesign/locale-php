<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-calendar-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Assert;

foreach (array(
    'case-1-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abc',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abc',
        6 => null,
    ),
    'case-1-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abc',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abc',
        6 => null,
    ),
    'case-2-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abc',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abc',
        6 => null,
    ),
    'case-2-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abc',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abc',
        6 => null,
    ),
    'case-3-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcd',
        6 => null,
    ),
    'case-3-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcd',
        6 => null,
    ),
    'case-4-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcd',
        6 => null,
    ),
    'case-4-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcd',
        6 => null,
    ),
    'case-5-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcde',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcde',
        6 => null,
    ),
    'case-5-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcde',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcde',
        6 => null,
    ),
    'case-6-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcde',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcde',
        6 => null,
    ),
    'case-6-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcde',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcde',
        6 => null,
    ),
    'case-7-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdef',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcdef',
        6 => null,
    ),
    'case-7-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdef',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcdef',
        6 => null,
    ),
    'case-8-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdef',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcdef',
        6 => null,
    ),
    'case-8-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdef',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcdef',
        6 => null,
    ),
    'case-9-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefg',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcdefg',
        6 => null,
    ),
    'case-9-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefg',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcdefg',
        6 => null,
    ),
    'case-10-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefg',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcdefg',
        6 => null,
    ),
    'case-10-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefg',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcdefg',
        6 => null,
    ),
    'case-11-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefgh',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcdefgh',
        6 => null,
    ),
    'case-11-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefgh',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcdefgh',
        6 => null,
    ),
    'case-12-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefgh',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-abcdefgh',
        6 => null,
    ),
    'case-12-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefgh',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-abcdefgh',
        6 => null,
    ),
    'case-13-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '12345678',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-12345678',
        6 => null,
    ),
    'case-13-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '12345678',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-12345678',
        6 => null,
    ),
    'case-14-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '12345678',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-12345678',
        6 => null,
    ),
    'case-14-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '12345678',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-12345678',
        6 => null,
    ),
    'case-15-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-1234abcd',
        6 => null,
    ),
    'case-15-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-1234abcd',
        6 => null,
    ),
    'case-16-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-1234abcd',
        6 => null,
    ),
    'case-16-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-1234abcd',
        6 => null,
    ),
    'case-17-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd-abc123',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-1234abcd-abc123',
        6 => null,
    ),
    'case-17-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L31:C3:assert.sameValue',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd-abc123',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-1234abcd-abc123',
        6 => null,
    ),
    'case-18-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd-abc123',
        ),
        4 => 'associative_array',
        5 => 'en-u-ca-1234abcd-abc123',
        6 => null,
    ),
    'case-18-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-valid.js:L36:C3:assert.sameValue',
        1 => 'en-u-ca-gregory',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '1234abcd-abc123',
        ),
        4 => 'plain_object',
        5 => 'en-u-ca-1234abcd-abc123',
        6 => null,
    ),
) as [$assertionId, $tag, $optionName, $value, $representation, $expected, $property]) {
    $result = $expected === RangeError::class
        ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
        : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected, $property);
    Assert::assertSame('passing', $result['status'], $assertionId . ': ' . ($result['failure'] ?? 'unknown failure'));
}
