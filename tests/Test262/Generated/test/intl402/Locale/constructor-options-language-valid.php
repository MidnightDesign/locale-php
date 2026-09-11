<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-language-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Assert;

foreach (array(
    'case-1-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L39:C3:assert.sameValue',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'stringable',
            'value' => 'de',
        ),
        4 => 'associative_array',
        5 => 'de',
        6 => null,
    ),
    'case-1-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L39:C3:assert.sameValue',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'stringable',
            'value' => 'de',
        ),
        4 => 'plain_object',
        5 => 'de',
        6 => null,
    ),
    'case-2-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L46:C3:assert.sameValue',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'stringable',
            'value' => 'de',
        ),
        4 => 'associative_array',
        5 => 'de-US',
        6 => null,
    ),
    'case-2-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L46:C3:assert.sameValue',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'stringable',
            'value' => 'de',
        ),
        4 => 'plain_object',
        5 => 'de-US',
        6 => null,
    ),
    'case-3-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L52:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'stringable',
            'value' => 'de',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-3-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L52:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'stringable',
            'value' => 'de',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-4-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'null',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-4-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'null',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-5-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'null',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-5-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'null',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-6-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'null',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-6-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'null',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-7-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'zh-cmn',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-7-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'zh-cmn',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-8-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'zh-cmn',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-8-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'zh-cmn',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-9-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'zh-cmn',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-9-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'zh-cmn',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-10-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ZH-CMN',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-10-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ZH-CMN',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-11-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ZH-CMN',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-11-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ZH-CMN',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-12-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ZH-CMN',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-12-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ZH-CMN',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-13-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-13-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L63:C3:assert.throws',
        1 => 'en',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-14-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-14-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L64:C3:assert.throws',
        1 => 'en-US',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'plain_object',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-15-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
        ),
        4 => 'associative_array',
        5 => 'Midnight\\Intl\\Exception\\RangeError',
        6 => null,
    ),
    'case-15-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-options-language-valid.js:L65:C3:assert.throws',
        1 => 'en-els',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'abcd',
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
