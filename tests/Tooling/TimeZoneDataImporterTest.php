<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\TimeZoneDataImporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TimeZoneDataImporter::class)]
final class TimeZoneDataImporterTest extends TestCase
{
    public function testItProjectsZonesLinksCountryBoundariesAndPendingRenames(): void
    {
        $projection = TimeZoneDataImporter::project(
            [
                <<<'TZDB'
                    Zone Area/Alpha 0 - A
                    Zone Area/Beta1 0 - B
                    Zone Area/Beta2 0 - B
                    Zone Area/New 0 - N
                    Zone Etc/UTC 0 - UTC
                    Link Area/Alpha Area/AlphaAlias
                    Link Area/Alpha Area/Charlie
                    Link Area/Alpha Area/BetaAlias
                    Link Area/New Area/Old
                    Link Etc/UTC UTC
                    TZDB,
            ],
            <<<'TAB'
                AA	+0000+00000	Area/Alpha
                BB	+0000+00000	Area/Beta1
                BB	+0000+00000	Area/Beta2
                CC	+0000+00000	Area/Charlie
                DD	+0000+00000	Area/New
                TAB,
            "Link Area/Beta2 Area/BetaAlias\n",
            <<<'XML'
                <type name="aaone" alias="Area/Alpha Area/AlphaAlias" iana="Area/Alpha"/>
                <type name="bbone" alias="Area/Beta1 Area/BetaAlias" iana="Area/Beta1"/>
                <type name="bbtwo" alias="Area/Beta2"/>
                <type name="ccone" alias="Area/Charlie"/>
                <type name="ddone" alias="Area/Old Area/New" iana="Area/New"/>
                XML,
            ['Area/New' => 'Area/Old'],
        );

        self::assertSame('Area/Alpha', $projection['primaryIdentifiers']['Area/AlphaAlias']);
        self::assertSame('Area/Charlie', $projection['primaryIdentifiers']['Area/Charlie']);
        self::assertSame('Area/Beta2', $projection['primaryIdentifiers']['Area/BetaAlias']);
        self::assertSame('Area/Old', $projection['primaryIdentifiers']['Area/New']);
        self::assertSame('Area/Old', $projection['primaryIdentifiers']['Area/Old']);
        self::assertSame('UTC', $projection['primaryIdentifiers']['Etc/UTC']);
        self::assertSame('UTC', $projection['primaryIdentifiers']['UTC']);
        self::assertSame(['Area/Old'], $projection['regions']['DD']);
    }
}
