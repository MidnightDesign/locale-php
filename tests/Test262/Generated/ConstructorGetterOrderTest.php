<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-getter-order.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Tests\Test262\Harness\OptionObservation;
use PHPUnit\Framework\TestCase;

final class ConstructorGetterOrderTest extends TestCase
{
    public function testTranslatedAssertion(): void
    {
        self::assertSame([
            'tag toString', 'get language', 'toString language', 'get script', 'toString script',
            'get region', 'toString region', 'get variants', 'toString variants',
            'get calendar', 'toString calendar', 'get collation', 'toString collation',
            'get hourCycle', 'toString hourCycle', 'get caseFirst', 'toString caseFirst',
            'get numeric', 'get numberingSystem', 'toString numberingSystem',
        ], OptionObservation::getterOrder());
    }
}
