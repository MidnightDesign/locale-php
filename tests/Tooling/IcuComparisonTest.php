<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\IcuComparison;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(IcuComparison::class)]
final class IcuComparisonTest extends TestCase
{
    public function testItExplainsAHostDifferenceWithoutMakingHostIcuAuthoritative(): void
    {
        self::assertSame(
            [
                'locale' => 'en-AE',
                'releaseSnapshotFirstDay' => 1,
                'hostIcuVersion' => '72.1',
                'hostIcuFirstDay' => 6,
                'result' => 'host-differs-from-release-snapshot',
                'semanticAuthority' => 'release-data-snapshot',
            ],
            IcuComparison::evidence('en-AE', 1, '72.1', 6),
        );
    }

    public function testItExplainsWhenHostIcuIsUnavailable(): void
    {
        self::assertSame(
            [
                'locale' => 'en-AE',
                'releaseSnapshotFirstDay' => 1,
                'hostIcuVersion' => null,
                'hostIcuFirstDay' => null,
                'result' => 'host-icu-unavailable',
                'semanticAuthority' => 'release-data-snapshot',
            ],
            IcuComparison::evidence('en-AE', 1, null, null),
        );
    }
}
