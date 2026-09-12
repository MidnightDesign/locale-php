<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests;

use Midnight\Intl\CaseFirst;
use Midnight\Intl\HourCycle;
use Midnight\Intl\Locale;
use Midnight\Intl\Spec\Locale as SpecLocale;
use Midnight\Intl\TextDirection;
use Midnight\Intl\TextInfo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
    public function testItProvidesALosslessPorcelainBoundary(): void
    {
        $locale = new Locale('EN-latn-us', language: 'fr', region: 'ca');
        $spec = $locale->toSpec();
        $roundTrip = Locale::fromSpec($spec);

        self::assertSame('fr-Latn-CA', $locale->toString());
        self::assertSame('fr-Latn-CA', (string) $locale);
        self::assertSame('"fr-Latn-CA"', json_encode($locale, JSON_THROW_ON_ERROR));
        self::assertSame('fr', $locale->language);
        self::assertSame('Latn', $locale->script);
        self::assertSame('CA', $locale->region);
        self::assertSame('fr-Latn-CA', $spec->toString());
        self::assertSame('fr-Latn-CA', $roundTrip->toString());
        self::assertNotSame($spec, $roundTrip->toSpec());
    }

    public function testPorcelainIsFinalAndUsesANativeStrictSignature(): void
    {
        self::assertTrue((new \ReflectionClass(Locale::class))->isFinal());

        $constructor = (new \ReflectionClass(Locale::class))->getConstructor();
        self::assertNotNull($constructor);
        $parameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter;
        }
        foreach ([
            'language',
            'script',
            'region',
            'variants',
            'calendar',
            'collation',
            'firstDayOfWeek',
            'numberingSystem',
        ] as $name) {
            self::assertSame('?string', (string) $parameters[$name]->getType());
            self::assertNull($parameters[$name]->getDefaultValue());
        }
        self::assertSame('Midnight\Intl\HourCycle|string|null', (string) $parameters['hourCycle']->getType());
        self::assertSame('Midnight\Intl\CaseFirst|string|null', (string) $parameters['caseFirst']->getType());

        $this->expectException(\TypeError::class);
        (new \ReflectionClass(Locale::class))->newInstance(null);
    }

    public function testItExposesCompleteCanonicalIdentifiersThroughThePorcelainLayer(): void
    {
        $locale = new Locale(
            'EN-fonipa-u-ca-gregory-zz-abc-x-private',
            calendar: 'islamicc',
            collation: 'phonebk',
            firstDayOfWeek: '7',
            hourCycle: 'h23',
            caseFirst: 'upper',
            numeric: true,
            numberingSystem: 'latn',
            variants: '1901',
        );

        self::assertSame(
            'en-1901-u-ca-islamic-civil-co-phonebk-fw-sun-hc-h23-kf-upper-kn-nu-latn-zz-abc-x-private',
            $locale->toString(),
        );
        self::assertSame('en-1901', $locale->baseName);
        self::assertSame('islamic-civil', $locale->calendar);
        self::assertSame('phonebk', $locale->collation);
        self::assertSame('sun', $locale->firstDayOfWeek);
        self::assertSame(HourCycle::H23, $locale->hourCycle);
        self::assertSame(CaseFirst::Upper, $locale->caseFirst);
        self::assertTrue($locale->numeric);
        self::assertSame('latn', $locale->numberingSystem);
        self::assertSame('1901', $locale->variants);
        $roundTrip = Locale::fromSpec($locale->toSpec());
        self::assertSame($locale->toString(), $roundTrip->toString());
        self::assertSame($locale->toString(), (string) $locale);
        self::assertSame(json_encode($locale->toString()), json_encode($locale));
    }

    public function testItUsesBackedEnumsForClosedKeywordContracts(): void
    {
        self::assertSame(['h11', 'h12', 'h23', 'h24'], array_column(HourCycle::cases(), 'value'));
        self::assertSame(['upper', 'lower', 'false'], array_column(CaseFirst::cases(), 'value'));

        $locale = new Locale('en-u-hc-h11-kf-lower', hourCycle: HourCycle::H24, caseFirst: CaseFirst::Upper);

        self::assertSame('en-u-hc-h24-kf-upper', $locale->toString());
        self::assertSame(HourCycle::H24, $locale->hourCycle);
        self::assertSame(CaseFirst::Upper, $locale->caseFirst);

        $backingValues = new Locale('en', hourCycle: 'h23', caseFirst: 'false');
        self::assertSame(HourCycle::H23, $backingValues->hourCycle);
        self::assertSame(CaseFirst::False, $backingValues->caseFirst);

        $openIdentifierValues = new Locale('en-u-hc-h00-kf-yes');
        self::assertSame('h00', $openIdentifierValues->hourCycle);
        self::assertSame('yes', $openIdentifierValues->caseFirst);

        $missingValues = new Locale('en');
        self::assertNull($missingValues->hourCycle);
        self::assertNull($missingValues->caseFirst);
    }

    public function testPorcelainRejectsReuseAndUninitializedAccess(): void
    {
        $locale = new Locale('en');

        try {
            $locale->__construct('fr');
            self::fail('A porcelain locale must not be initialized twice.');
        } catch (\TypeError) {
            self::assertSame('en', $locale->toString());
        }

        $uninitialized = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();

        try {
            $uninitialized->toSpec();
            self::fail('An uninitialized porcelain locale must fail.');
        } catch (\TypeError $error) {
            self::assertSame('Locale is not initialized.', $error->getMessage());
        }
    }

    public function testPorcelainPropertiesAreReadOnlyAndReportPresence(): void
    {
        $locale = new Locale('en-US');

        self::assertTrue(isset($locale->baseName));
        self::assertTrue(isset($locale->region));
        self::assertFalse(isset($locale->script));
        self::assertFalse(isset($locale->calendar));

        $this->expectException(\TypeError::class);
        $locale->__set('language', 'fr');
    }

    public function testItReturnsAFreshPrimaryTimeZoneList(): void
    {
        $locale = new Locale('en-US');
        $first = $locale->getTimeZones();
        self::assertNotNull($first);
        $first[] = 'Injected/Mutation';

        $second = $locale->getTimeZones();
        self::assertNotNull($second);
        self::assertNotContains('Injected/Mutation', $second);
    }

    public function testItStrengthensLocalePreferenceListsAtThePorcelainBoundary(): void
    {
        $calendarLocale = new Locale('en-u-ca-islamic');
        $hourCycleLocale = new Locale('en-u-hc-h11');

        self::assertSame(['islamic'], $calendarLocale->getCalendars());
        self::assertSame([HourCycle::H11], $hourCycleLocale->getHourCycles());

        $calendars = (new Locale('th'))->getCalendars();
        $hourCycles = (new Locale('en'))->getHourCycles();
        self::assertContainsOnly('string', $calendars);
        // @phpstan-ignore staticMethod.alreadyNarrowedType (runtime porcelain contract)
        self::assertContainsOnlyInstancesOf(HourCycle::class, $hourCycles);

        $calendars[] = 'injected';
        $hourCycles[] = HourCycle::H24;
        self::assertNotContains('injected', (new Locale('th'))->getCalendars());
        self::assertNotSame($hourCycles, (new Locale('en'))->getHourCycles());
    }

    public function testItReturnsAFreshNumberingSystemList(): void
    {
        $locale = new Locale('fa');
        $first = $locale->getNumberingSystems();
        $first[] = 'injected';

        self::assertNotContains('injected', $locale->getNumberingSystems());
    }

    public function testItReturnsAFreshCollationList(): void
    {
        $locale = new Locale('de');
        $first = $locale->getCollations();
        $first[] = 'injected';

        self::assertNotContains('injected', $locale->getCollations());
    }

    public function testItReturnsFreshPorcelainLikelySubtagValues(): void
    {
        $locale = new Locale('zh-Hant');
        $original = $locale->toString();
        $maximal = $locale->maximize();
        $minimal = $maximal->minimize();

        self::assertSame($original, $locale->toString());
        self::assertInstanceOf(Locale::class, $maximal);
        self::assertInstanceOf(Locale::class, $minimal);
        self::assertNotSame($locale, $maximal);
        self::assertNotSame($maximal, $minimal);
        self::assertNotSame($locale->toSpec(), $maximal->toSpec());
    }

    public function testItReturnsFreshTypedTextInformation(): void
    {
        $locale = new Locale('ar');

        $first = $locale->getTextInfo();
        $second = $locale->getTextInfo();

        self::assertInstanceOf(TextInfo::class, $first);
        self::assertSame(TextDirection::RightToLeft, $first->direction);
        self::assertNotSame($first, $second);
        self::assertSame('ar', $locale->toString());
    }

    public function testTextInformationUsesANullableReadonlyDirection(): void
    {
        $info = (new Locale('en-Zzzz'))->getTextInfo();

        self::assertNull($info->direction);
        self::assertTrue((new \ReflectionClass(TextInfo::class))->isReadOnly());
        self::assertTrue((new \ReflectionProperty(TextInfo::class, 'direction'))->isReadOnly());
    }
}
