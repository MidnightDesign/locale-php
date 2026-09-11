<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FirstDayOfWeekValidIdentifierTest extends TestCase
{
    /** @return array<string, array{string, string, ?array<string, mixed>, string, string, string|bool|null}> */
    public static function cases(): array
    {
        return array(
  'case-1-direct' =>
  array(
    0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
    1 => 'en-u-fw-mon',
    2 => null,
    3 => 'direct',
    4 => 'firstDayOfWeek',
    5 => 'mon',
  ),
  'case-2-direct' =>
  array(
    0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
    1 => 'en-u-fw-tue',
    2 => null,
    3 => 'direct',
    4 => 'firstDayOfWeek',
    5 => 'tue',
  ),
  'case-3-direct' =>
  array(
    0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
    1 => 'en-u-fw-wed',
    2 => null,
    3 => 'direct',
    4 => 'firstDayOfWeek',
    5 => 'wed',
  ),
  'case-4-direct' =>
  array(
    0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
    1 => 'en-u-fw-thu',
    2 => null,
    3 => 'direct',
    4 => 'firstDayOfWeek',
    5 => 'thu',
  ),
  'case-5-direct' =>
  array(
    0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
    1 => 'en-u-fw-fri',
    2 => null,
    3 => 'direct',
    4 => 'firstDayOfWeek',
    5 => 'fri',
  ),
  'case-6-direct' =>
  array(
    0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
    1 => 'en-u-fw-sat',
    2 => null,
    3 => 'direct',
    4 => 'firstDayOfWeek',
    5 => 'sat',
  ),
  'case-7-direct' =>
  array(
    0 => 'test/intl402/Locale/prototype/firstDayOfWeek/valid-id.js:L25:C3:assert.sameValue',
    1 => 'en-u-fw-sun',
    2 => null,
    3 => 'direct',
    4 => 'firstDayOfWeek',
    5 => 'sun',
  ),
);
    }

    /** @param array<string, mixed>|null $options */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(string $assertionId, string $tag, ?array $options, string $representation, string $property, string|bool|null $expected): void
    {
        $locale = match ($representation) {
            'direct' => new Locale($tag),
            'associative_array' => new Locale($tag, $options),
            'plain_object' => new Locale($tag, (object) $options),
            default => throw new \InvalidArgumentException('Unsupported representation.'),
        };
        $actual = $property === 'toString' ? $locale->toString() : $locale->{$property};

        self::assertSame($expected, $actual, $assertionId);
    }
}
