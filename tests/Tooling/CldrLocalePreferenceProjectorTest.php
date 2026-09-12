<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\CldrLocalePreferenceProjector;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CldrLocalePreferenceProjector::class)]
final class CldrLocalePreferenceProjectorTest extends TestCase
{
    public function testCalendarProjectionDoesNotDependOnXmlAttributeOrder(): void
    {
        $calendarBcp47 = <<<'XML'
            <key name="ca">
                <type name="gregory"/>
                <type name="islamicc" preferred="islamic-civil"/>
            </key>
            XML;
        $supplementalData = <<<'XML'
            <calendarPreference ordering="gregory islamicc" draft="contributed" territories="001 US"/>
            XML;

        self::assertSame(
            [
                'available' => ['gregory', 'islamic-civil'],
                'preferences' => [
                    '001' => ['gregory', 'islamic-civil'],
                    'US' => ['gregory', 'islamic-civil'],
                ],
            ],
            CldrLocalePreferenceProjector::calendars($calendarBcp47, $supplementalData),
        );
    }

    public function testHourCycleProjectionPreservesPreferredThenAllowedOrder(): void
    {
        $supplementalData = <<<'XML'
            <hours regions="001 en_CA" allowed="h H K" preferred="K"/>
            XML;

        self::assertSame(
            [
                '001' => ['h11', 'h12', 'h23'],
                'en-CA' => ['h11', 'h12', 'h23'],
            ],
            CldrLocalePreferenceProjector::hourCycles($supplementalData),
        );
    }

    public function testWeekProjectionExpandsTerritoriesAndIgnoresAlternateRows(): void
    {
        $supplementalData = <<<'XML'
            <weekData>
                <firstDay day="mon" territories="001 AE"/>
                <firstDay day="sat" territories="AE" alt="variant"/>
                <weekendStart day="sat" territories="001 AE"/>
                <weekendStart day="fri" territories="IR"/>
                <weekendEnd day="sun" territories="001 AE"/>
                <weekendEnd day="fri" territories="IR"/>
            </weekData>
            XML;

        self::assertSame(
            [
                'firstDay' => ['001' => 1, 'AE' => 1],
                'weekend' => [
                    '001' => [6, 7],
                    'AE' => [6, 7],
                    'IR' => [5],
                ],
            ],
            CldrLocalePreferenceProjector::weekInfo($supplementalData),
        );
    }
}
