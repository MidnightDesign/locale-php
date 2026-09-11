<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-canonicalized.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
use PHPUnit\Framework\Assert;

foreach (array(
    'scenario-1-direct' => array(
        0 => 'en-u-ca-islamic-civil',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L36:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
            ),
        ),
    ),
    'scenario-2-associative_array' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'islamic-civil',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L45:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
            ),
        ),
    ),
    'scenario-2-plain_object' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'islamic-civil',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L45:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
            ),
        ),
    ),
    'scenario-3-direct' => array(
        0 => 'en-u-ca-islamicc',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L54:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
            ),
        ),
    ),
    'scenario-4-associative_array' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'islamicc',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L63:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
            ),
        ),
    ),
    'scenario-4-plain_object' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'islamicc',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L63:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
            ),
        ),
    ),
    'scenario-5-direct' => array(
        0 => 'en-u-ca-ethioaa',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L36:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'ethioaa',
            ),
        ),
    ),
    'scenario-6-associative_array' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'ethioaa',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L45:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'ethioaa',
            ),
        ),
    ),
    'scenario-6-plain_object' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'ethioaa',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L45:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'ethioaa',
            ),
        ),
    ),
    'scenario-7-direct' => array(
        0 => 'en-u-ca-ethiopic-amete-alem',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L54:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'ethioaa',
            ),
        ),
    ),
    'scenario-8-associative_array' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'ethiopic-amete-alem',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L63:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'ethioaa',
            ),
        ),
    ),
    'scenario-8-plain_object' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'ethiopic-amete-alem',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/constructor-options-canonicalized.js:L63:C5:assert.sameValue',
                1 => 'calendar',
                2 => 'ethioaa',
            ),
        ),
    ),
) as [$tag, $options, $representation, $expectationTuples]) {
    $expectations = array_map(LocaleStateExpectation::fromTuple(...), $expectationTuples);
    $results = LocaleStateAssertion::evaluate($tag, $options, $representation, $expectations);
    foreach ($expectations as $index => $expectation) {
        $result = $results[$index];
        Assert::assertSame(
            'passing',
            $result['status'],
            $expectation->assertionId . ': ' . ($result['failure'] ?? 'unknown failure'),
        );
    }
}
