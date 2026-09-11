<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/calendar/canonicalize.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CalendarCanonicalizeTest extends TestCase
{
    /** @return array<string, array{string, string, ?array<string, mixed>, string, string, string|bool|null}> */
    public static function cases(): array
    {
        return array(
            'case-1-associative_array' => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L19:C1:assert.sameValue',
                1 => 'en',
                2 => array(
                    'calendar' => 'islamicc',
                ),
                3 => 'associative_array',
                4 => 'toString',
                5 => 'en-u-ca-islamic-civil',
            ),
            'case-2-plain_object' => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L19:C1:assert.sameValue',
                1 => 'en',
                2 => array(
                    'calendar' => 'islamicc',
                ),
                3 => 'plain_object',
                4 => 'toString',
                5 => 'en-u-ca-islamic-civil',
            ),
            'case-3-associative_array' => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L22:C1:assert.sameValue',
                1 => 'en',
                2 => array(
                    'calendar' => 'islamicc',
                ),
                3 => 'associative_array',
                4 => 'calendar',
                5 => 'islamic-civil',
            ),
            'case-4-plain_object' => array(
                0 => 'test/intl402/Locale/prototype/calendar/canonicalize.js:L22:C1:assert.sameValue',
                1 => 'en',
                2 => array(
                    'calendar' => 'islamicc',
                ),
                3 => 'plain_object',
                4 => 'calendar',
                5 => 'islamic-civil',
            ),
        );
    }

    /** @param array<string, mixed>|null $options */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(
        string $assertionId,
        string $tag,
        ?array $options,
        string $representation,
        string $property,
        string|bool|null $expected,
    ): void {
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
