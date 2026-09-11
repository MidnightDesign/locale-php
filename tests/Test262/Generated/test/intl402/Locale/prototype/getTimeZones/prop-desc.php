<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/getTimeZones/prop-desc.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

Assert::assertTrue(method_exists(Locale::class, 'getTimeZones'));
$method = new ReflectionMethod(Locale::class, 'getTimeZones');
Assert::assertTrue($method->isPublic() && !$method->isStatic());
