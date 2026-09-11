<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-apply-options-canonicalizes-twice.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Assert;

foreach (array(
    'case-1-associative_array' => array(
        0 => 'test/intl402/Locale/constructor-apply-options-canonicalizes-twice.js:L48:C1:assert.sameValue',
        1 => 'und-Armn-SU',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ru',
        ),
        4 => 'associative_array',
        5 => 'ru-Armn-AM',
    ),
    'case-1-plain_object' => array(
        0 => 'test/intl402/Locale/constructor-apply-options-canonicalizes-twice.js:L48:C1:assert.sameValue',
        1 => 'und-Armn-SU',
        2 => 'language',
        3 => array(
            'type' => 'string',
            'value' => 'ru',
        ),
        4 => 'plain_object',
        5 => 'ru-Armn-AM',
    ),
) as [$assertionId, $tag, $optionName, $value, $representation, $expected]) {
    $result = $expected === RangeError::class
        ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
        : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected);
    Assert::assertSame('passing', $result['status'], $assertionId . ': ' . ($result['failure'] ?? 'unknown failure'));
}
