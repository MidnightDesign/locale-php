<?php

declare(strict_types=1);

namespace Midnight\Intl\Spec;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Data\PrimaryTimeZones;
use Midnight\Intl\Internal\EcmaValue;
use Midnight\Intl\Internal\LocaleIdentifier;
use Midnight\Intl\Internal\LocaleOptions;
use Midnight\Intl\Internal\LocalePreferences;
use Midnight\Intl\Internal\UndefinedValue;

/**
 * @property-read string $baseName
 * @property-read string $language
 * @property-read string|null $script
 * @property-read string|null $region
 * @property-read string|null $variants
 * @property-read string|null $calendar
 * @property-read string|null $caseFirst
 * @property-read string|null $collation
 * @property-read string|null $firstDayOfWeek
 * @property-read string|null $hourCycle
 * @property-read string|null $numberingSystem
 * @property-read bool $numeric
 * @psalm-api
 */
class Locale
{
    private LocaleIdentifier $identifier;

    private bool $initialized = false;

    /** @var array<string, mixed> */
    private array $consumerProperties = [];

    public function __construct(mixed $tag, mixed $options = UndefinedValue::Value)
    {
        if ($this->initialized) {
            throw new TypeError('Locale is already initialized.');
        }

        $tag = match (true) {
            is_string($tag) => $tag,
            $tag instanceof self => self::initializedTag($tag),
            default => EcmaValue::toLocaleTag($tag),
        };
        $options = LocaleOptions::from($options);

        $this->identifier = LocaleIdentifier::parse($tag);

        $languageOption = $options->read('language');
        if ($languageOption->present) {
            $this->identifier->replaceLanguageId(
                EcmaValue::toString($languageOption->value),
                $this->identifier->script,
                $this->identifier->region,
                $this->identifier->variants === [] ? null : implode('-', $this->identifier->variants),
            );
        }

        $scriptOption = $options->read('script');
        if ($scriptOption->present) {
            $this->identifier->replaceLanguageId(
                $this->identifier->language,
                EcmaValue::toString($scriptOption->value),
                $this->identifier->region,
                $this->identifier->variants === [] ? null : implode('-', $this->identifier->variants),
            );
        }

        $regionOption = $options->read('region');
        if ($regionOption->present) {
            $this->identifier->replaceLanguageId(
                $this->identifier->language,
                $this->identifier->script,
                EcmaValue::toString($regionOption->value),
                $this->identifier->variants === [] ? null : implode('-', $this->identifier->variants),
            );
        }

        $variantsOption = $options->read('variants');
        if ($variantsOption->present) {
            $this->identifier->replaceLanguageId(
                $this->identifier->language,
                $this->identifier->script,
                $this->identifier->region,
                EcmaValue::toString($variantsOption->value),
            );
        }

        self::applyStringKeywordOption($this->identifier, $options, 'calendar', 'ca');
        self::applyStringKeywordOption($this->identifier, $options, 'collation', 'co');
        self::applyFirstDayOfWeekOption($this->identifier, $options);
        self::applyClosedKeywordOption($this->identifier, $options, 'hourCycle', 'hc', [
            'h11',
            'h12',
            'h23',
            'h24',
        ]);
        self::applyClosedKeywordOption($this->identifier, $options, 'caseFirst', 'kf', ['upper', 'lower', 'false']);
        self::applyNumericOption($this->identifier, $options);
        self::applyStringKeywordOption($this->identifier, $options, 'numberingSystem', 'nu');
        $this->initialized = true;
    }

    public function __get(string $name): mixed
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return match (true) {
            array_key_exists($name, $this->consumerProperties) => $this->consumerProperties[$name],
            $name === 'baseName' => $this->identifier->baseName(),
            $name === 'language' => $this->identifier->language,
            $name === 'script' => $this->identifier->script,
            $name === 'region' => $this->identifier->region,
            $name === 'variants' => $this->identifier->variants === []
                ? null
                : implode('-', $this->identifier->variants),
            $name === 'calendar' => $this->identifier->keyword('ca'),
            $name === 'caseFirst' => $this->identifier->keyword('kf'),
            $name === 'collation' => $this->identifier->keyword('co'),
            $name === 'firstDayOfWeek' => $this->identifier->keyword('fw'),
            $name === 'hourCycle' => $this->identifier->keyword('hc'),
            $name === 'numberingSystem' => $this->identifier->keyword('nu'),
            $name === 'numeric' => in_array($this->identifier->keyword('kn'), ['', 'true'], true),
            default => throw new \Error(sprintf('Undefined property %s::$%s.', self::class, $name)),
        };
    }

    public function __set(string $name, mixed $value): void
    {
        if (self::isPublicProperty($name)) {
            throw new TypeError(sprintf('Locale property "%s" is read-only.', $name));
        }

        $this->consumerProperties[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return (
            (array_key_exists($name, $this->consumerProperties) || self::isDeliveredProperty($name))
            && $this->__get($name) !== null
        );
    }

    private static function isDeliveredProperty(string $name): bool
    {
        return match ($name) {
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
                => true,
            default => false,
        };
    }

    private static function isPublicProperty(string $name): bool
    {
        return match ($name) {
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
                => true,
            default => false,
        };
    }

    public function toString(): string
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return $this->identifier->toString();
    }

    /** @return list<string>|null */
    public function getTimeZones(): ?array
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return PrimaryTimeZones::forRegion($this->identifier->region);
    }

    public function maximize(): self
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return new self($this->identifier->maximize()->toString());
    }

    public function minimize(): self
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return new self($this->identifier->minimize()->toString());
    }

    /** @return array{direction: 'ltr'|'rtl'|null} */
    public function getTextInfo(): array
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return ['direction' => $this->identifier->textDirection()];
    }

    private static function initializedTag(self $locale): string
    {
        if (!$locale->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return $locale->identifier->toString();
    }

    private static function applyStringKeywordOption(
        LocaleIdentifier $identifier,
        LocaleOptions $options,
        string $optionName,
        string $key,
    ): void {
        $option = $options->read($optionName);
        if (!$option->present) {
            return;
        }

        $value = EcmaValue::toString($option->value);
        if (preg_match('/\A[A-Za-z0-9]{3,8}(?:-[A-Za-z0-9]{3,8})*\z/', $value) !== 1) {
            throw new RangeError(sprintf('Invalid %s option: "%s".', $optionName, $value));
        }
        $identifier->setKeyword($key, $value);
    }

    /**
     * @param list<string> $allowed
     */
    private static function applyClosedKeywordOption(
        LocaleIdentifier $identifier,
        LocaleOptions $options,
        string $optionName,
        string $key,
        array $allowed,
    ): void {
        $option = $options->read($optionName);
        if (!$option->present) {
            return;
        }

        $value = EcmaValue::toString($option->value);
        if (!in_array($value, $allowed, true)) {
            throw new RangeError(sprintf('Invalid %s option: "%s".', $optionName, $value));
        }
        $identifier->setKeyword($key, $value);
    }

    private static function applyFirstDayOfWeekOption(LocaleIdentifier $identifier, LocaleOptions $options): void
    {
        $option = $options->read('firstDayOfWeek');
        if (!$option->present) {
            return;
        }

        $value = EcmaValue::toString($option->value);
        $value = match ($value) {
            '0', '7' => 'sun',
            '1' => 'mon',
            '2' => 'tue',
            '3' => 'wed',
            '4' => 'thu',
            '5' => 'fri',
            '6' => 'sat',
            default => $value,
        };
        if (preg_match('/\A[A-Za-z0-9]{3,8}(?:-[A-Za-z0-9]{3,8})*\z/', $value) !== 1) {
            throw new RangeError(sprintf('Invalid firstDayOfWeek option: "%s".', $value));
        }
        $identifier->setKeyword('fw', $value);
    }

    private static function applyNumericOption(LocaleIdentifier $identifier, LocaleOptions $options): void
    {
        $option = $options->read('numeric');
        if (!$option->present) {
            return;
        }

        $identifier->setKeyword('kn', EcmaValue::toBoolean($option->value) ? 'true' : 'false');
    }

    /** @return non-empty-list<string> */
    public function getCalendars(): array
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return LocalePreferences::calendars($this->identifier);
    }

    /** @return non-empty-list<string> */
    public function getHourCycles(): array
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return LocalePreferences::hourCycles($this->identifier);
    }

    /** @return list<string> */
    public function getNumberingSystems(): array
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        $numberingSystem = $this->identifier->keyword('nu');

        return [
            $numberingSystem ?? \Midnight\Intl\Internal\Data\NumberingSystems::defaultFor(
                $this->identifier->baseName(),
            ),
        ];
    }
}
