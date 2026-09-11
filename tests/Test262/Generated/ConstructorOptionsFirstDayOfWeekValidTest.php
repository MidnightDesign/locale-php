<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsFirstDayOfWeekValidTest extends TestCase
{
    /** @return array<string, array{string, string, string, array<string, mixed>, string, string|bool, ?string}> */
    public static function cases(): array
    {
        return array(
  'case-1-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'mon',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-1-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'mon',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-2-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'mon',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-2-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'mon',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-3-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tue',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-3-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tue',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-4-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tue',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-4-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tue',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-5-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'wed',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-5-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'wed',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-6-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'wed',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-6-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'wed',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-7-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'thu',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-7-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'thu',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-8-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'thu',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-8-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'thu',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-9-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fri',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-9-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fri',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-10-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fri',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-10-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'fri',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-11-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sat',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-11-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sat',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-12-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sat',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-12-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sat',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-13-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sun',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-13-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sun',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-14-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sun',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-14-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sun',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-15-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '1',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-15-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '1',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-16-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '1',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-16-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '1',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-17-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '2',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-17-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '2',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-18-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '2',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-18-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '2',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-19-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '3',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-19-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '3',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-20-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '3',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-20-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '3',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-21-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '4',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-21-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '4',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-22-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '4',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-22-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '4',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-23-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '5',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-23-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '5',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-24-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '5',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-24-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '5',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-25-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '6',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-25-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '6',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-26-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '6',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-26-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '6',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-27-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '7',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-27-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '7',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-28-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '7',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-28-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '7',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-29-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '0',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-29-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '0',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-30-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '0',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-30-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => '0',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-31-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 1,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-31-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 1,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-32-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 1,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-32-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 1,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-mon',
    6 => null,
  ),
  'case-33-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 2,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-33-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 2,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-34-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 2,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-34-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 2,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tue',
    6 => null,
  ),
  'case-35-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 3,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-35-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 3,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-36-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 3,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-36-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 3,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-wed',
    6 => null,
  ),
  'case-37-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 4,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-37-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 4,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-38-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 4,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-38-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 4,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-thu',
    6 => null,
  ),
  'case-39-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 5,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-39-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 5,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-40-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 5,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-40-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 5,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-fri',
    6 => null,
  ),
  'case-41-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 6,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-41-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 6,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-42-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 6,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-42-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 6,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sat',
    6 => null,
  ),
  'case-43-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 7,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-43-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 7,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-44-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 7,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-44-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 7,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-45-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 0,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-45-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 0,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-46-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 0,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-46-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'int',
      'value' => 0,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sun',
    6 => null,
  ),
  'case-47-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => true,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw',
    6 => null,
  ),
  'case-47-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => true,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw',
    6 => null,
  ),
  'case-48-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => true,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw',
    6 => null,
  ),
  'case-48-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => true,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw',
    6 => null,
  ),
  'case-49-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-false',
    6 => null,
  ),
  'case-49-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-false',
    6 => null,
  ),
  'case-50-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-false',
    6 => null,
  ),
  'case-50-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'bool',
      'value' => false,
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-false',
    6 => null,
  ),
  'case-51-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'null',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-null',
    6 => null,
  ),
  'case-51-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'null',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-null',
    6 => null,
  ),
  'case-52-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'null',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-null',
    6 => null,
  ),
  'case-52-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'null',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-null',
    6 => null,
  ),
  'case-53-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'primidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-primidi',
    6 => null,
  ),
  'case-53-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'primidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-primidi',
    6 => null,
  ),
  'case-54-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'primidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-primidi',
    6 => null,
  ),
  'case-54-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'primidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-primidi',
    6 => null,
  ),
  'case-55-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'duodi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-duodi',
    6 => null,
  ),
  'case-55-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'duodi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-duodi',
    6 => null,
  ),
  'case-56-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'duodi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-duodi',
    6 => null,
  ),
  'case-56-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'duodi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-duodi',
    6 => null,
  ),
  'case-57-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tridi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tridi',
    6 => null,
  ),
  'case-57-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tridi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tridi',
    6 => null,
  ),
  'case-58-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tridi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tridi',
    6 => null,
  ),
  'case-58-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tridi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tridi',
    6 => null,
  ),
  'case-59-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quartidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-quartidi',
    6 => null,
  ),
  'case-59-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quartidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-quartidi',
    6 => null,
  ),
  'case-60-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quartidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-quartidi',
    6 => null,
  ),
  'case-60-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quartidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-quartidi',
    6 => null,
  ),
  'case-61-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quintidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-quintidi',
    6 => null,
  ),
  'case-61-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quintidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-quintidi',
    6 => null,
  ),
  'case-62-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quintidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-quintidi',
    6 => null,
  ),
  'case-62-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'quintidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-quintidi',
    6 => null,
  ),
  'case-63-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sextidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sextidi',
    6 => null,
  ),
  'case-63-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sextidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sextidi',
    6 => null,
  ),
  'case-64-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sextidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-sextidi',
    6 => null,
  ),
  'case-64-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'sextidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-sextidi',
    6 => null,
  ),
  'case-65-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'septidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-septidi',
    6 => null,
  ),
  'case-65-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'septidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-septidi',
    6 => null,
  ),
  'case-66-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'septidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-septidi',
    6 => null,
  ),
  'case-66-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'septidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-septidi',
    6 => null,
  ),
  'case-67-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'octidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-octidi',
    6 => null,
  ),
  'case-67-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'octidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-octidi',
    6 => null,
  ),
  'case-68-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'octidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-octidi',
    6 => null,
  ),
  'case-68-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'octidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-octidi',
    6 => null,
  ),
  'case-69-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'nonidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-nonidi',
    6 => null,
  ),
  'case-69-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'nonidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-nonidi',
    6 => null,
  ),
  'case-70-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'nonidi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-nonidi',
    6 => null,
  ),
  'case-70-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'nonidi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-nonidi',
    6 => null,
  ),
  'case-71-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'decadi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-decadi',
    6 => null,
  ),
  'case-71-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'decadi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-decadi',
    6 => null,
  ),
  'case-72-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'decadi',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-decadi',
    6 => null,
  ),
  'case-72-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'decadi',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-decadi',
    6 => null,
  ),
  'case-73-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-frank',
    6 => null,
  ),
  'case-73-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-frank',
    6 => null,
  ),
  'case-74-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-frank',
    6 => null,
  ),
  'case-74-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-frank',
    6 => null,
  ),
  'case-75-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yungfong',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-yungfong',
    6 => null,
  ),
  'case-75-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yungfong',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-yungfong',
    6 => null,
  ),
  'case-76-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yungfong',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-yungfong',
    6 => null,
  ),
  'case-76-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yungfong',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-yungfong',
    6 => null,
  ),
  'case-77-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yung-fong',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-yung-fong',
    6 => null,
  ),
  'case-77-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yung-fong',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-yung-fong',
    6 => null,
  ),
  'case-78-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yung-fong',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-yung-fong',
    6 => null,
  ),
  'case-78-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'yung-fong',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-yung-fong',
    6 => null,
  ),
  'case-79-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tang',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tang',
    6 => null,
  ),
  'case-79-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tang',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tang',
    6 => null,
  ),
  'case-80-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tang',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-tang',
    6 => null,
  ),
  'case-80-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'tang',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-tang',
    6 => null,
  ),
  'case-81-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank-yung-fong-tang',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-frank-yung-fong-tang',
    6 => null,
  ),
  'case-81-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L70:C3:assert.sameValue',
    1 => 'en',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank-yung-fong-tang',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-frank-yung-fong-tang',
    6 => null,
  ),
  'case-82-associative_array' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank-yung-fong-tang',
    ),
    4 => 'associative_array',
    5 => 'en-u-fw-frank-yung-fong-tang',
    6 => null,
  ),
  'case-82-plain_object' =>
  array(
    0 => 'test/intl402/Locale/constructor-options-firstDayOfWeek-valid.js:L75:C3:assert.sameValue',
    1 => 'en-u-fw-WED',
    2 => 'firstDayOfWeek',
    3 =>
    array(
      'type' => 'string',
      'value' => 'frank-yung-fong-tang',
    ),
    4 => 'plain_object',
    5 => 'en-u-fw-frank-yung-fong-tang',
    6 => null,
  ),
);
    }

    /** @param array<string, mixed> $value */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(string $assertionId, string $tag, string $optionName, array $value, string $representation, string|bool $expected, ?string $property): void
    {
        $result = $expected === RangeError::class
            ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
            : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected, $property);
        self::assertSame('passing', $result['status'], $assertionId.': '.($result['failure'] ?? 'unknown failure'));
    }
}
