<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests;

use Midnight\Intl\Locale;
use Midnight\Intl\Spec\Locale as SpecLocale;
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

        $this->expectException(\TypeError::class);
        (new \ReflectionClass(Locale::class))->newInstance(null);
    }

    public function testItExposesCompleteCanonicalIdentifiersThroughThePorcelainLayer(): void
    {
        $locale = new Locale('EN-fonipa-u-ca-gregory-x-private', calendar: 'islamicc', numeric: true, variants: '1901');

        self::assertSame('en-1901-u-ca-islamic-civil-kn-x-private', $locale->toString());
        self::assertSame('en-1901', $locale->baseName);
        self::assertSame('islamic-civil', $locale->calendar);
        self::assertTrue($locale->numeric);
        self::assertSame('1901', $locale->variants);
        self::assertSame($locale->toString(), Locale::fromSpec($locale->toSpec())->toString());
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
}
