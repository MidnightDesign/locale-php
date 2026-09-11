<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/likely-subtags.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LikelySubtagsTest extends TestCase
{
    /** @return iterable<string, array{string, string}> */
    public static function maximalCases(): iterable
    {
        foreach (array(
            'en' => 'en-Latn-US',
            'en-Latn' => 'en-Latn-US',
            'en-Shaw' => 'en-Shaw-GB',
            'en-Arab' => 'en-Arab-US',
            'en-US' => 'en-Latn-US',
            'en-GB' => 'en-Latn-GB',
            'en-FR' => 'en-Latn-FR',
            'it-Kana-CA' => 'it-Kana-CA',
            'und' => 'en-Latn-US',
            'und-Thai' => 'th-Thai-TH',
            'und-419' => 'es-Latn-419',
            'und-150' => 'en-Latn-150',
            'und-AT' => 'de-Latn-AT',
            'und-Cyrl-RO' => 'bg-Cyrl-RO',
            'und-AQ' => 'en-Latn-AQ',
        ) as $tag => $maximal) {
            yield $tag . ' maximal fixed point' => [$maximal, $maximal];
            foreach (array(
                0 => '',
                1 => '-fonipa',
                2 => '-a-not-assigned',
                3 => '-u-attr',
                4 => '-u-co',
                5 => '-u-co-phonebk',
                6 => '-x-private',
            ) as $extra) {
                yield $tag . $extra => [$tag . $extra, $maximal . $extra];
            }
        }
    }

    /** @return iterable<string, array{string, string}> */
    public static function minimalCases(): iterable
    {
        foreach (array(
            'en' => 'en',
            'en-Latn' => 'en',
            'ar-Arab' => 'ar',
            'en-US' => 'en',
            'en-GB' => 'en-GB',
            'en-Latn-US' => 'en',
            'en-Shaw-GB' => 'en-Shaw',
            'en-Arab-US' => 'en-Arab',
            'en-Latn-GB' => 'en-GB',
            'en-Latn-FR' => 'en-FR',
            'it-Kana-CA' => 'it-Kana-CA',
            'th-Thai-TH' => 'th',
            'es-Latn-419' => 'es-419',
            'ru-Cyrl-RU' => 'ru',
            'de-Latn-AT' => 'de-AT',
            'bg-Cyrl-RO' => 'bg-RO',
            'und-Latn-AQ' => 'en-AQ',
        ) as $tag => $minimal) {
            yield $tag . ' minimal fixed point' => [$minimal, $minimal];
            foreach (array(
                0 => '',
                1 => '-fonipa',
                2 => '-a-not-assigned',
                3 => '-u-attr',
                4 => '-u-co',
                5 => '-u-co-phonebk',
                6 => '-x-private',
            ) as $extra) {
                yield $tag . $extra => [$tag . $extra, $minimal . $extra];
            }
        }
    }

    #[DataProvider('maximalCases')]
    public function testTranslatedMaximizeAssertions(string $tag, string $expected): void
    {
        self::assertSame($expected, (new Locale($tag))->maximize()->toString());
    }

    #[DataProvider('minimalCases')]
    public function testTranslatedMinimizeAssertions(string $tag, string $expected): void
    {
        self::assertSame($expected, (new Locale($tag))->minimize()->toString());
    }

    public function testTranslatedPrivateUseRejectionAssertion(): void
    {
        $this->expectException(RangeError::class);

        new Locale('x-private');
    }
}
