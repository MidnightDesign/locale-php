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
        new Locale('not-a-locale');
    }

    public function testItAcceptsInitializedLocalesAndStringableObjects(): void
    {
        $source = new Locale('en-US');
        $fromLocale = new Locale($source, ['region' => 'GB']);
        $fromObject = new Locale(new class () implements \Stringable {
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

    public function testItCanonicalizesAliasesIntroducedByOptions(): void
    {
        self::assertSame('sr-Latn', (new Locale('en', ['language' => 'sh']))->toString());
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
                return true;
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
        foreach (['', "en\n", 'root', 'abcd', 'en_US', 'en--US', 'en-1901', 'en-u-ca-gregory'] as $tag) {
            try {
                new Locale($tag);
                self::fail(sprintf('Expected "%s" to be rejected.', $tag));
            } catch (RangeError) {
                self::addToAssertionCount(1);
            }
        }

        foreach ([
            ['language' => 'fr-FR'],
            ['language' => "english\n"],
            ['script' => 'abc'],
            ['script' => "Latn\n"],
            ['region' => 'USA'],
            ['region' => "US\n"],
        ] as $options) {
            try {
                new Locale('en', $options);
                self::fail('Expected the invalid option to be rejected.');
            } catch (RangeError) {
                self::addToAssertionCount(1);
            }
        }
    }

    public function testItConvertsBooleanOptionsUsingJavaScriptStrings(): void
    {
        self::assertSame('en-True', (new Locale('en', ['script' => true]))->toString());

        $this->expectException(RangeError::class);
        new Locale('en', ['script' => false]);
    }

    public function testItRejectsOptionsThatCannotBeConvertedToStrings(): void
    {
        $this->expectException(TypeError::class);
        new Locale('en', ['language' => []]);
    }

    public function testUninitializedSubclassesFailTheBrandCheck(): void
    {
        $locale = new class () extends Locale {
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

    public function testItDoesNotPresentUndeliveredPropertiesAsImplemented(): void
    {
        try {
            (new Locale('en'))->__get('calendar');
            self::fail('An undelivered property must not be readable.');
        } catch (\Error $error) {
            self::assertSame(
                'Undefined property Midnight\\Intl\\Spec\\Locale::$calendar.',
                $error->getMessage(),
            );
        }
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

    public function testUninitializedSubclassesRejectPropertyAccess(): void
    {
        $locale = new class () extends Locale {
            public function __construct()
            {
            }
        };

        $this->expectException(TypeError::class);
        $locale->__get('language');
    }
}

final class OptionAccessLog
{
    /** @var list<string> */
    public array $entries = [];
}
