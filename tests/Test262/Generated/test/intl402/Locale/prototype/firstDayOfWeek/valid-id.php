<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
use PHPUnit\Framework\Assert;

foreach (array(
    'scenario-1-direct' => array(
        0 => 'en-u-fw-mon',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-2-direct' => array(
        0 => 'en-u-fw-tue',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-3-direct' => array(
        0 => 'en-u-fw-wed',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-4-direct' => array(
        0 => 'en-u-fw-thu',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-5-direct' => array(
        0 => 'en-u-fw-fri',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-6-direct' => array(
        0 => 'en-u-fw-sat',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-7-direct' => array(
        0 => 'en-u-fw-sun',
        1 => null,
        2 => 'direct',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
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
