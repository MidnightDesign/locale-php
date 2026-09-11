<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
use PHPUnit\Framework\Assert;

foreach (array(
    'scenario-1-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'mon',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-1-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'mon',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-2-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'mon',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-2-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'mon',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-3-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'tue',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-3-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'tue',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-4-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'tue',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-4-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'tue',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-5-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'wed',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-5-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'wed',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-6-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'wed',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-6-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'wed',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-7-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'thu',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-7-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'thu',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-8-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'thu',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-8-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'thu',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-9-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'fri',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-9-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'fri',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-10-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'fri',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-10-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'fri',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-11-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'sat',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-11-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'sat',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-12-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'sat',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-12-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'sat',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-13-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'sun',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-13-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 'sun',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-14-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'sun',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-14-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 'sun',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-15-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '1',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-15-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '1',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-16-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '1',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-16-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '1',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-17-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '2',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-17-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '2',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-18-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '2',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-18-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '2',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-19-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '3',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-19-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '3',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-20-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '3',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-20-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '3',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-21-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '4',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-21-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '4',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-22-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '4',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-22-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '4',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-23-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '5',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-23-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '5',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-24-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '5',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-24-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '5',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-25-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '6',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-25-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '6',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-26-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '6',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-26-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '6',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-27-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '7',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-27-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '7',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-28-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '7',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-28-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '7',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-29-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '0',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-29-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => '0',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-30-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '0',
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-30-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => '0',
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-31-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 1,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-31-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 1,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-32-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 1,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-32-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 1,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'mon',
            ),
        ),
    ),
    'scenario-33-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 2,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-33-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 2,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-34-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 2,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-34-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 2,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'tue',
            ),
        ),
    ),
    'scenario-35-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 3,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-35-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 3,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-36-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 3,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-36-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 3,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'wed',
            ),
        ),
    ),
    'scenario-37-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 4,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-37-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 4,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-38-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 4,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-38-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 4,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'thu',
            ),
        ),
    ),
    'scenario-39-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 5,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-39-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 5,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-40-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 5,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-40-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 5,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'fri',
            ),
        ),
    ),
    'scenario-41-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 6,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-41-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 6,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-42-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 6,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-42-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 6,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sat',
            ),
        ),
    ),
    'scenario-43-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 7,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-43-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 7,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-44-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 7,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-44-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 7,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-45-associative_array' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 0,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-45-plain_object' => array(
        0 => 'en',
        1 => array(
            'firstDayOfWeek' => 0,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L41:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-46-associative_array' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 0,
        ),
        2 => 'associative_array',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
                1 => 'firstDayOfWeek',
                2 => 'sun',
            ),
        ),
    ),
    'scenario-46-plain_object' => array(
        0 => 'en-u-fw-WED',
        1 => array(
            'firstDayOfWeek' => 0,
        ),
        2 => 'plain_object',
        3 => array(
            0 => array(
                0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-options.js:L46:C3:assert.sameValue',
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
