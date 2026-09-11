<?php

declare(strict_types=1);

// Copyright 2020 André Bargull. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/minimize/removing-likely-subtags-first-adds-likely-subtags.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RemoveLikelySubtagsTest extends TestCase
{
    /** @return iterable<string, array{string, string}> */
    public static function cases(): iterable
    {
        foreach (array(
            'und' => 'en',
            'und-Thai' => 'th',
            'und-419' => 'es-419',
            'und-150' => 'en-150',
            'und-AT' => 'de-AT',
            'aae-Latn-IT' => 'aae',
            'aae-Thai-CO' => 'aae-Thai-CO',
            'und-CW' => 'pap',
            'und-US' => 'en',
            'zh-Hant' => 'zh-TW',
            'zh-Hani' => 'zh-Hani',
        ) as $tag => $minimal) {
            yield $tag . ' fixed point' => [$minimal, $minimal];
            yield $tag => [$tag, $minimal];
        }
    }

    #[DataProvider('cases')]
    public function testTranslatedRemoveLikelySubtagsAssertions(string $tag, string $expected): void
    {
        self::assertSame($expected, (new Locale($tag))->minimize()->toString());
    }
}
