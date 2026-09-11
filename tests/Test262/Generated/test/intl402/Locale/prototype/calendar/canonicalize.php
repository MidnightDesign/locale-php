<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/calendar/canonicalize.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
use PHPUnit\Framework\Assert;

foreach (array(
    'scenario-1-associative_array' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'islamicc',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L19:C1:assert.sameValue',
                1 => 'toString',
                2 => 'en-u-ca-islamic-civil',
            ),
            1 => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L22:C1:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
            ),
        ),
    ),
    'scenario-1-plain_object' => array(
        0 => 'en',
        1 => array(
            'calendar' => 'islamicc',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L19:C1:assert.sameValue',
                1 => 'toString',
                2 => 'en-u-ca-islamic-civil',
            ),
            1 => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L22:C1:assert.sameValue',
                1 => 'calendar',
                2 => 'islamic-civil',
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
