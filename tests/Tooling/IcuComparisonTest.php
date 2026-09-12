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
                'releaseDataSnapshotFirstDay' => 1,
                'hostIcuVersion' => '72.1',
                'hostIcuFirstDay' => 6,
                'result' => 'host-differs-from-release-data-snapshot',
                'semanticAuthority' => 'release-data-snapshot',
                'hostCapabilities' => [
                    'intl-calendar-week-info' => true,
                    'intl-time-zone-iana-id' => false,
                ],
                'missingCapabilityFallbacks' => [
                    'intl-time-zone-iana-id' => 'release-data-snapshot',
                ],
            ],
            IcuComparison::evidence('en-AE', 1, '72.1', 6, [
                'intl-calendar-week-info' => true,
                'intl-time-zone-iana-id' => false,
            ]),
        );
    }

    public function testItExplainsWhenHostIcuIsUnavailable(): void
    {
        self::assertSame(
            [
                'locale' => 'en-AE',
                'releaseDataSnapshotFirstDay' => 1,
                'hostIcuVersion' => null,
                'hostIcuFirstDay' => null,
                'result' => 'host-icu-unavailable',
                'semanticAuthority' => 'release-data-snapshot',
                'hostCapabilities' => ['intl-calendar-week-info' => false],
                'missingCapabilityFallbacks' => [
                    'intl-calendar-week-info' => 'release-data-snapshot',
                ],
            ],
            IcuComparison::evidence('en-AE', 1, null, null, ['intl-calendar-week-info' => false]),
        );
    }
}
