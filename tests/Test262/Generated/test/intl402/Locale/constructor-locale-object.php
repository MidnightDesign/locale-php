<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-locale-object.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

$enUS = new Locale('en-US');
$enGB = new Locale($enUS, ['region' => 'GB']);
Assert::assertSame('en-US', $enUS->toString());
Assert::assertSame('en-GB', $enGB->toString());

$zhUnihan = new Locale('zh-u-co-unihan');
$zhZhuyin = new Locale($zhUnihan, (object) ['collation' => 'zhuyin']);
Assert::assertSame('zh-u-co-unihan', $zhUnihan->toString());
Assert::assertSame('zh-u-co-zhuyin', $zhZhuyin->toString());
Assert::assertSame('unihan', $zhUnihan->collation);
Assert::assertSame('zhuyin', $zhZhuyin->collation);
