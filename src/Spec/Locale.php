<?php

declare(strict_types=1);

namespace Midnight\Intl\Spec;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\LocaleIdentifier;
use Midnight\Intl\Internal\OptionValue;
use Midnight\Intl\Internal\Test262\OptionBag;

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
 */
#[\AllowDynamicProperties]
class Locale
{
    private const PUBLIC_PROPERTIES = [
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
    ];

    private LocaleIdentifier $identifier;

    private bool $initialized = false;

    public function __construct(mixed $tag, mixed $options = null)
    {
        if ($this->initialized) {
            throw new TypeError('Locale is already initialized.');
        }

        $tag = match (true) {
            is_string($tag) => $tag,
            $tag instanceof self => $tag->toString(),
            $tag instanceof \Stringable => (string) $tag,
            default => throw new TypeError('The locale tag must be a string or an object.'),
        };

        if (func_num_args() > 1 && $options === null) {
            throw new TypeError('The locale options must not be null.');
        }

        $this->identifier = LocaleIdentifier::parse($tag);

        if (is_array($options) || is_object($options)) {
            $languageOption = self::readOption($options, 'language');
            $language = $languageOption->present
                ? self::toStringValue($languageOption->value)
                : $this->identifier->language;
            $scriptOption = self::readOption($options, 'script');
            $script = $scriptOption->present
                ? self::toStringValue($scriptOption->value)
                : $this->identifier->script;
            $regionOption = self::readOption($options, 'region');
            $region = $regionOption->present
                ? self::toStringValue($regionOption->value)
                : $this->identifier->region;
            $variantsOption = self::readOption($options, 'variants');
            $variants = $variantsOption->present
                ? self::toStringValue($variantsOption->value)
                : ($this->identifier->variants === [] ? null : implode('-', $this->identifier->variants));

            $this->identifier->replaceLanguageId(
                $language,
                $script,
                $region,
                $variants,
            );

            self::applyStringKeywordOption($this->identifier, $options, 'calendar', 'ca');
            self::applyStringKeywordOption($this->identifier, $options, 'collation', 'co');
            self::applyFirstDayOfWeekOption($this->identifier, $options);
            self::applyClosedKeywordOption($this->identifier, $options, 'hourCycle', 'hc', ['h11', 'h12', 'h23', 'h24']);
            self::applyClosedKeywordOption($this->identifier, $options, 'caseFirst', 'kf', ['upper', 'lower', 'false']);
            self::applyNumericOption($this->identifier, $options);
            self::applyStringKeywordOption($this->identifier, $options, 'numberingSystem', 'nu');
        }
        $this->initialized = true;
    }

    public function __get(string $name): mixed
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return match ($name) {
            'baseName' => $this->identifier->baseName(),
            'language' => $this->identifier->language,
            'script' => $this->identifier->script,
            'region' => $this->identifier->region,
            'variants' => $this->identifier->variants === [] ? null : implode('-', $this->identifier->variants),
            'calendar' => $this->identifier->keyword('ca'),
            'caseFirst' => $this->identifier->keyword('kf'),
            'collation' => $this->identifier->keyword('co'),
            'firstDayOfWeek' => $this->identifier->keyword('fw'),
            'hourCycle' => $this->identifier->keyword('hc'),
            'numberingSystem' => $this->identifier->keyword('nu'),
            'numeric' => in_array($this->identifier->keyword('kn'), ['', 'true'], true),
            default => throw new \Error(sprintf('Undefined property %s::$%s.', self::class, $name)),
        };
    }

    public function __set(string $name, mixed $value): void
    {
        if (in_array($name, self::PUBLIC_PROPERTIES, true)) {
            throw new TypeError(sprintf('Locale property "%s" is read-only.', $name));
        }

        $this->{$name} = $value;
    }

    public function __isset(string $name): bool
    {
        return in_array($name, self::PUBLIC_PROPERTIES, true)
            && $this->__get($name) !== null;
    }

    public function toString(): string
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return $this->identifier->toString();
    }

    /**
     * @param array<array-key, mixed>|object $options
     */
    private static function readOption(array|object $options, string $name): OptionValue
    {
        if ($options instanceof OptionBag) {
            return $options->has($name)
                ? OptionValue::present($options->get($name))
                : OptionValue::missing();
        }

        if (is_array($options)) {
            return array_key_exists($name, $options)
                ? OptionValue::present($options[$name])
                : OptionValue::missing();
        }

        return property_exists($options, $name)
            ? OptionValue::present($options->{$name})
            : OptionValue::missing();
    }

    private static function toStringValue(mixed $value): string
    {
        if (is_string($value) || is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        throw new TypeError('Locale option cannot be converted to a string.');
    }

    /** @param array<array-key, mixed>|object $options */
    private static function applyStringKeywordOption(
        LocaleIdentifier $identifier,
        array|object $options,
        string $optionName,
        string $key,
    ): void
    {
        $option = self::readOption($options, $optionName);
        if (!$option->present) {
            return;
        }

        $value = self::toStringValue($option->value);
        if (preg_match('/^[A-Za-z0-9]{3,8}(?:-[A-Za-z0-9]{3,8})*$/D', $value) !== 1) {
            throw new RangeError(sprintf('Invalid %s option: "%s".', $optionName, $value));
        }
        $identifier->setKeyword($key, strtolower($value));
    }

    /**
     * @param array<array-key, mixed>|object $options
     * @param list<string> $allowed
     */
    private static function applyClosedKeywordOption(
        LocaleIdentifier $identifier,
        array|object $options,
        string $optionName,
        string $key,
        array $allowed,
    ): void
    {
        $option = self::readOption($options, $optionName);
        if (!$option->present) {
            return;
        }

        $value = self::toStringValue($option->value);
        if (!in_array($value, $allowed, true)) {
            throw new RangeError(sprintf('Invalid %s option: "%s".', $optionName, $value));
        }
        $identifier->setKeyword($key, $value);
    }

    /** @param array<array-key, mixed>|object $options */
    private static function applyFirstDayOfWeekOption(LocaleIdentifier $identifier, array|object $options): void
    {
        $option = self::readOption($options, 'firstDayOfWeek');
        if (!$option->present) {
            return;
        }

        $value = self::toStringValue($option->value);
        $weekdays = [
            '0' => 'sun', '7' => 'sun', 'sun' => 'sun', 'sunday' => 'sun',
            '1' => 'mon', 'mon' => 'mon', 'monday' => 'mon',
            '2' => 'tue', 'tue' => 'tue', 'tuesday' => 'tue',
            '3' => 'wed', 'wed' => 'wed', 'wednesday' => 'wed',
            '4' => 'thu', 'thu' => 'thu', 'thursday' => 'thu',
            '5' => 'fri', 'fri' => 'fri', 'friday' => 'fri',
            '6' => 'sat', 'sat' => 'sat', 'saturday' => 'sat',
        ];
        if (!isset($weekdays[$value])) {
            throw new RangeError(sprintf('Invalid firstDayOfWeek option: "%s".', $value));
        }
        $identifier->setKeyword('fw', $weekdays[$value]);
    }

    /** @param array<array-key, mixed>|object $options */
    private static function applyNumericOption(LocaleIdentifier $identifier, array|object $options): void
    {
        $option = self::readOption($options, 'numeric');
        if (!$option->present) {
            return;
        }

        $identifier->setKeyword('kn', self::toBooleanValue($option->value) ? 'true' : 'false');
    }

    private static function toBooleanValue(mixed $value): bool
    {
        return match (true) {
            $value === null, $value === false, $value === 0, $value === 0.0, $value === '' => false,
            default => true,
        };
    }
}
