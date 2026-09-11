<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-script-invalid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Assert;

foreach (array(
    'case-1-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => '',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-1-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => '',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-2-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'a',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-2-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'a',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-3-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'ab',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-3-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'ab',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-4-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'abc',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-4-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'abc',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-5-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'abc7',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-5-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'abc7',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-6-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'notascript',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-6-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'notascript',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-7-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'undefined',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-7-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'undefined',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-8-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Balİ',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-8-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Balİ',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-9-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Balı',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-9-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Balı',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-10-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'ary-Arab',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-10-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'ary-Arab',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-11-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-SA',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-11-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-SA',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-12-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-vaidika',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-12-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-vaidika',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-13-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-a-asdf',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-13-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-a-asdf',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-14-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-x-private',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-14-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'string',
            'value' => 'Latn-x-private',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-15-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'int',
            'value' => 7,
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
    'case-15-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-script-invalid.js:L49:C3:assert.throws',
        1 => 'en',
        2 => 'script',
        3 => array(
            'type' => 'int',
            'value' => 7,
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
    ),
) as [$assertionId, $tag, $optionName, $value, $representation, $expected]) {
    $result = $expected === RangeError::class
        ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
        : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected);
    Assert::assertSame('passing', $result['status'], $assertionId . ': ' . ($result['failure'] ?? 'unknown failure'));
}
