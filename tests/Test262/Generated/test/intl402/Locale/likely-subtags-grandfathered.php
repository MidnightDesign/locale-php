<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/likely-subtags-grandfathered.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach (array(
    0 => 'en-GB-oed',
    1 => 'i-ami',
    2 => 'i-bnn',
    3 => 'i-default',
    4 => 'i-enochian',
    5 => 'i-hak',
    6 => 'i-klingon',
    7 => 'i-lux',
    8 => 'i-mingo',
    9 => 'i-navajo',
    10 => 'i-pwn',
    11 => 'i-tao',
    12 => 'i-tay',
    13 => 'i-tsu',
    14 => 'sgn-BE-FR',
    15 => 'sgn-BE-NL',
    16 => 'sgn-CH-DE',
    17 => 'no-bok',
    18 => 'no-nyn',
    19 => 'zh-min',
    20 => 'zh-min-nan',
) as $tag) {
    try {
        new Locale($tag);
        Assert::fail('Expected grandfathered locale ' . $tag . ' to be rejected.');
    } catch (RangeError) {
        Assert::assertTrue(true);
    }
}

$assertLocale = static function (string $tag, string $canonical, string $maximal, string $minimal): void {
    $locale = new Locale($tag);
    Assert::assertSame($canonical, $locale->toString());
    Assert::assertSame($maximal, $locale->maximize()->toString());
    Assert::assertSame($maximal, $locale->maximize()->maximize()->toString());
    Assert::assertSame($minimal, $locale->minimize()->toString());
    Assert::assertSame($minimal, $locale->minimize()->minimize()->toString());
    Assert::assertSame($minimal, $locale->maximize()->minimize()->toString());
    Assert::assertSame($maximal, $locale->minimize()->maximize()->toString());
};

foreach (array(
    0 => array(
        0 => 'art-lojban',
        1 => 'jbo',
        2 => 'jbo-Latn-001',
        3 => 'jbo',
    ),
    1 => array(
        0 => 'cel-gaulish',
        1 => 'xtg',
        2 => 'xtg',
        3 => 'xtg',
    ),
    2 => array(
        0 => 'zh-guoyu',
        1 => 'zh',
        2 => 'zh-Hans-CN',
        3 => 'zh',
    ),
    3 => array(
        0 => 'zh-hakka',
        1 => 'hak',
        2 => 'hak-Hans-CN',
        3 => 'hak',
    ),
    4 => array(
        0 => 'zh-xiang',
        1 => 'hsn',
        2 => 'hsn-Hans-CN',
        3 => 'hsn',
    ),
) as [$tag, $canonical, $maximal, $minimal]) {
    $assertLocale($tag, $canonical, $maximal, $minimal);
    foreach (array(
        0 => 'fonipa',
        1 => 'a-not-assigned',
        2 => 'u-attr',
        3 => 'u-co',
        4 => 'u-co-phonebk',
        5 => 'x-private',
    ) as $extra) {
        $assertLocale($tag . '-' . $extra, $canonical . '-' . $extra, $maximal . '-' . $extra, $minimal . '-' . $extra);
    }
}
