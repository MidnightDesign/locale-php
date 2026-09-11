<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-locale-object.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\TestCase;

final class ConstructorLocaleObjectTest extends TestCase
{
    public function testTranslatedAssertions(): void
    {
        $enUS = new Locale('en-US');
        $enGB = new Locale($enUS, ['region' => 'GB']);
        self::assertSame('en-US', $enUS->toString());
        self::assertSame('en-GB', $enGB->toString());

        $zhUnihan = new Locale('zh-u-co-unihan');
        $zhZhuyin = new Locale($zhUnihan, (object) ['collation' => 'zhuyin']);
        self::assertSame('zh-u-co-unihan', $zhUnihan->toString());
        self::assertSame('zh-u-co-zhuyin', $zhZhuyin->toString());
        self::assertSame('unihan', $zhUnihan->collation);
        self::assertSame('zhuyin', $zhZhuyin->collation);
    }
}
