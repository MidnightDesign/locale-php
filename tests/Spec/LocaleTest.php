<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Spec;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
    public function testItConstructsACanonicalBasicLocale(): void
    {
        $locale = new Locale('EN-latn-us');

        self::assertSame('en-Latn-US', $locale->toString());
        self::assertSame('en-Latn-US', $locale->baseName);
        self::assertSame('en', $locale->language);
        self::assertSame('Latn', $locale->script);
        self::assertSame('US', $locale->region);
        self::assertTrue(isset($locale->script));
    }

    public function testItCanonicalizesTheCompleteLocaleIdentifierGrammar(): void
    {
        $locale = new Locale(
            'DE-fonipa-1901-U-FOO-foo-NU-LATN-CA-ISLAMICC-ca-buddhist-T-es-419-H0-HYBRID-A-FOOBAR-X-Private',
        );

        self::assertSame(
            'de-1901-fonipa-a-foobar-t-es-419-h0-hybrid-u-foo-ca-islamic-civil-nu-latn-x-private',
            $locale->toString(),
        );
        self::assertSame('de-1901-fonipa', $locale->baseName);
        self::assertSame('1901-fonipa', $locale->variants);
        self::assertSame('islamic-civil', $locale->calendar);
        self::assertSame('latn', $locale->numberingSystem);
        self::assertNull($locale->caseFirst);
        self::assertFalse($locale->numeric);
    }

    /** @return iterable<string, array{string}> */
    public static function structurallyInvalidIdentifiers(): iterable
    {
        yield 'private use without language' => ['x-private'];
        yield 'duplicate variant' => ['de-1901-1901'];
        yield 'duplicate singleton' => ['en-a-foo-A-bar'];
        yield 'extension without value' => ['en-a'];
        yield 'Unicode extension with one-character value' => ['en-u-ca-a'];
        yield 'transformed extension without language or field' => ['en-t'];
        yield 'duplicate transformed variant' => ['en-t-de-1901-1901'];
        yield 'non-ASCII letters' => ["d\u{00E9}"];
    }

    #[DataProvider('structurallyInvalidIdentifiers')]
    public function testItRejectsStructurallyInvalidIdentifiers(string $tag): void
    {
        $this->expectException(RangeError::class);

        new Locale($tag);
    }

    public function testItAcceptsOneCharacterPrivateUseSubtags(): void
    {
        self::assertSame('en-x-a', (new Locale('en-x-a'))->toString());
        self::assertSame('en-x-private-a', (new Locale('en-x-private-a'))->toString());
    }

    public function testExplicitNullOptionsUseTheSpecTypeError(): void
    {
        $this->expectException(TypeError::class);
        new Locale('en', null);
    }

    public function testItAcceptsStringableObjects(): void
    {
        $fromObject = new Locale(new class() implements \Stringable {
            public function __toString(): string
            {
                return 'de-latn-de';
            }
        });

        self::assertSame('de-Latn-DE', $fromObject->toString());
    }

    public function testConstructorCannotReinitializeSpecLocale(): void
    {
        $locale = new Locale('de');

        $this->expectException(TypeError::class);
        $locale->__construct('fr');
    }

    public function testItCanonicalizesPinnedAliasData(): void
    {
        self::assertSame('he-Zinh-NZ', (new Locale('iw-Qaai-554'))->toString());
        self::assertSame('id-MM', (new Locale('in-BU'))->toString());
        self::assertSame('sr-Latn', (new Locale('sh'))->toString());
        self::assertSame('sr-ME', (new Locale('cnr'))->toString());
    }

    public function testItCanonicalizesTheInputBeforeApplyingOptions(): void
    {
        self::assertSame('en-Latn', (new Locale('sh', ['language' => 'en']))->toString());
    }

    public function testItCanonicalizesAliasesIntroducedByOptions(): void
    {
        self::assertSame('sr-Latn', (new Locale('en', ['language' => 'sh']))->toString());
    }

    public function testItRejectsInvalidValuesWithinTheDeliveredSlice(): void
    {
        foreach (['', "en\n", 'root', 'abcd', 'en_US', 'en--US', 'en-a', 'x-private'] as $tag) {
            try {
                new Locale($tag);
                self::fail(sprintf('Expected "%s" to be rejected.', $tag));
            } catch (RangeError) {
                self::addToAssertionCount(1);
            }
        }
    }

    public function testItRejectsOptionsThatCannotBeConvertedToStrings(): void
    {
        $this->expectException(TypeError::class);
        new Locale('en', ['language' => []]);
    }

    public function testItPresentsDeliveredProperties(): void
    {
        $locale = new Locale('en');

        self::assertNull($locale->calendar);
        self::assertNull($locale->variants);
        self::assertFalse($locale->numeric);
    }

    public function testItReportsOnlyPresentDeliveredProperties(): void
    {
        $locale = new Locale('en-US');

        self::assertTrue(isset($locale->baseName));
        self::assertTrue(isset($locale->language));
        self::assertTrue(isset($locale->region));
        self::assertFalse(isset($locale->script));
        self::assertFalse(isset($locale->calendar));
        self::assertFalse(isset($locale->unknown));
    }

    public function testEveryPublicPropertyIsReadOnly(): void
    {
        $locale = new Locale('en');

        foreach ([
            'baseName',
            'calendar',
            'caseFirst',
            'collation',
            'firstDayOfWeek',
            'hourCycle',
            'language',
            'numberingSystem',
            'numeric',
            'region',
            'script',
            'variants',
        ] as $property) {
            try {
                $locale->__set($property, 'value');
                self::fail(sprintf('Expected %s to be read-only.', $property));
            } catch (TypeError) {
                self::addToAssertionCount(1);
            }
        }
    }
}
