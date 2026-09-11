<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-calendar-invalid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Assert;

foreach (array(
    'case-1-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-1-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => '',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-2-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'a',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-2-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'a',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-3-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'ab',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-3-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'ab',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-4-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefghi',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-4-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abcdefghi',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-5-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abc-abcdefghi',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-5-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-calendar-invalid.js:L33:C3:assert.throws',
        1 => 'en',
        2 => 'calendar',
        3 => array(
            'type' => 'string',
            'value' => 'abc-abcdefghi',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
) as [$assertionId, $tag, $optionName, $value, $representation, $expected, $property]) {
    $result = $expected === RangeError::class
        ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
        : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected, $property);
    Assert::assertSame('passing', $result['status'], $assertionId . ': ' . ($result['failure'] ?? 'unknown failure'));
}
