<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/getWeekInfo/branding.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Tests\Test262\Harness\ReceiverBranding;
use PHPUnit\Framework\Assert;

Assert::assertTrue(ReceiverBranding::methodIsAvailable('getWeekInfo'));

Assert::assertNotContains(false, array_column(ReceiverBranding::method('getWeekInfo', true), 'passing'));
