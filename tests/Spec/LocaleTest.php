<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Spec;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Test262\OptionBag;
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

    public function testItExposesUnicodeKeywordPropertiesAndAppliesAllConstructorOptions(): void
    {
        $locale = new Locale('en-u-ca-gregory-kn-kf-lower', [
            'calendar' => 'islamicc',
            'caseFirst' => 'upper',
            'collation' => 'phonebk',
            'firstDayOfWeek' => '7',
            'hourCycle' => 'h23',
            'language' => 'fr',
            'numberingSystem' => 'latn',
            'numeric' => false,
            'region' => 'ca',
            'script' => 'latn',
            'variants' => 'FONIPA-1901',
        ]);

        self::assertSame(
            'fr-Latn-CA-1901-fonipa-u-ca-islamic-civil-co-phonebk-fw-sun-hc-h23-kf-upper-kn-false-nu-latn',
            $locale->toString(),
        );
        self::assertSame('fr-Latn-CA-1901-fonipa', $locale->baseName);
        self::assertSame('islamic-civil', $locale->calendar);
        self::assertSame('upper', $locale->caseFirst);
        self::assertSame('phonebk', $locale->collation);
        self::assertSame('sun', $locale->firstDayOfWeek);
        self::assertSame('h23', $locale->hourCycle);
        self::assertSame('latn', $locale->numberingSystem);
        self::assertFalse($locale->numeric);
        self::assertSame('1901-fonipa', $locale->variants);
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

    /** @return iterable<string, array{array<string, string>|object}> */
    public static function optionBags(): iterable
    {
        $options = [
            'language' => 'fr',
            'script' => 'cyrl',
            'region' => 'ca',
        ];

        yield 'associative array' => [$options];
        yield 'plain object' => [(object) $options];
    }

    /** @param array<string, string>|object $options */
    #[DataProvider('optionBags')]
    public function testItAppliesBaseComponentOptions(array|object $options): void
    {
        $locale = new Locale('en-Latn-US', $options);

        self::assertSame('fr-Cyrl-CA', $locale->toString());
    }

    public function testItUsesTheSpecExceptionTaxonomy(): void
    {
        try {
            new Locale(null);
            self::fail('A null tag should fail.');
        } catch (TypeError $error) {
            self::assertInstanceOf(\TypeError::class, $error);
        }

        try {
            new Locale('en', null);
            self::fail('Explicit null options should fail.');
        } catch (TypeError $error) {
            self::assertInstanceOf(\TypeError::class, $error);
        }

        $this->expectException(RangeError::class);
        new Locale('en-a');
    }

    public function testItAcceptsInitializedLocalesAndStringableObjects(): void
    {
        $source = new Locale('en-US');
        $fromLocale = new Locale($source, ['region' => 'GB']);
        $fromObject = new Locale(new class implements \Stringable {
            public function __toString(): string
            {
                return 'de-latn-de';
            }
        });

        self::assertSame('en-GB', $fromLocale->toString());
        self::assertSame('de-Latn-DE', $fromObject->toString());
    }

    public function testItProtectsBrandedStateWhileRemainingExtensible(): void
    {
        $locale = new class ('de') extends Locale {
            public bool $consumerState = false;
        };
        $locale->consumerState = true;

        self::assertTrue($locale->consumerState);

        try {
            $locale->__set('language', 'fr');
            self::fail('Locale state should be non-writable.');
        } catch (TypeError) {
            self::assertSame('de', $locale->language);
        }

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
        self::assertSame(
            'en-Latn',
            (new Locale('sh', ['language' => 'en']))->toString(),
        );
    }

    public function testItReadsAndConvertsBehavioralOptionsInOrder(): void
    {
        $log = new OptionAccessLog();
        $options = new class ($log) implements OptionBag {
            public function __construct(private OptionAccessLog $log)
            {
            }

            public function has(string $name): bool
            {
                return in_array($name, ['language', 'script', 'region'], true);
            }

            public function get(string $name): mixed
            {
                $this->log->entries[] = 'get:'.$name;

                return new class ($name, $this->log) implements \Stringable {
                    public function __construct(private string $name, private OptionAccessLog $log)
                    {
                    }

                    public function __toString(): string
                    {
                        $this->log->entries[] = 'convert:'.$this->name;

                        return match ($this->name) {
                            'language' => 'fr',
                            'script' => 'Latn',
                            'region' => 'CA',
                            default => throw new \LogicException('Unexpected option name.'),
                        };
                    }
                };
            }
        };

        self::assertSame('fr-Latn-CA', (new Locale('en', $options))->toString());
        self::assertSame([
            'get:language',
            'convert:language',
            'get:script',
            'convert:script',
            'get:region',
            'convert:region',
        ], $log->entries);
    }

    public function testItRejectsInvalidValuesWithinTheDeliveredSlice(): void
    {
        foreach (['', 'root', 'abcd', 'en_US', 'en--US', 'en-a', 'x-private'] as $tag) {
            try {
                new Locale($tag);
                self::fail(sprintf('Expected "%s" to be rejected.', $tag));
            } catch (RangeError) {
                self::addToAssertionCount(1);
            }
        }

        foreach ([
            ['language' => 'fr-FR'],
            ['script' => 'abc'],
            ['region' => 'USA'],
        ] as $options) {
            try {
                new Locale('en', $options);
                self::fail('Expected the invalid option to be rejected.');
            } catch (RangeError) {
                self::addToAssertionCount(1);
            }
        }
    }

    public function testUninitializedSubclassesFailTheBrandCheck(): void
    {
        $locale = new class extends Locale {
            public function __construct()
            {
            }
        };

        $this->expectException(TypeError::class);
        $locale->toString();
    }

    public function testUserStringConversionExceptionsPropagateUnchanged(): void
    {
        $failure = new \RuntimeException('consumer conversion failed');
        $tag = new class ($failure) implements \Stringable {
            public function __construct(private \RuntimeException $failure)
            {
            }

            public function __toString(): string
            {
                throw $this->failure;
            }
        };

        try {
            new Locale($tag);
            self::fail('Expected the consumer exception to propagate.');
        } catch (\RuntimeException $caught) {
            self::assertSame($failure, $caught);
        }
    }

    public function testItPresentsDeliveredProperties(): void
    {
        $locale = new Locale('en');

        self::assertNull($locale->calendar);
        self::assertNull($locale->variants);
        self::assertFalse($locale->numeric);
    }
}

final class OptionAccessLog
{
    /** @var list<string> */
    public array $entries = [];
}
