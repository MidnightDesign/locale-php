<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-unicode-ext-valid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach (array(
    'da-u-ca-gregory-ca-buddhist' => 'da-u-ca-gregory',
    'zh-u-nu-hans-ca-chinese' => 'zh-u-ca-chinese-nu-hans',
    'zh-u-ca-chinese-nu-hans' => 'zh-u-ca-chinese-nu-hans',
    'de-u-cu-eur-nu-latn' => 'de-u-cu-eur-nu-latn',
    'de-u-nu-latn-cu-eur' => 'de-u-cu-eur-nu-latn',
    'pt-u-attr-ca-gregory' => 'pt-u-attr-ca-gregory',
    'pt-u-attr1-attr2-ca-gregory' => 'pt-u-attr1-attr2-ca-gregory',
    'pt-u-attr2-attr1-ca-gregory' => 'pt-u-attr1-attr2-ca-gregory',
) as $tag => $expected) {
    Assert::assertSame($expected, (new Locale($tag))->toString());
}
