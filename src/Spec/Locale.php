<?php

declare(strict_types=1);

namespace Midnight\Intl\Spec;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Data\PrimaryTimeZones;
use Midnight\Intl\Internal\LocaleIdentifier;
use Midnight\Intl\Internal\OptionValue;
use Midnight\Intl\Internal\Test262\ObjectValue;
use Midnight\Intl\Internal\Test262\OptionBag;
use Midnight\Intl\Internal\Test262\SymbolValue;
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
            $tag instanceof ObjectValue => self::toStringValue(self::toPrimitive($tag)),
            $tag instanceof \Stringable => (string) $tag,
            default => throw new TypeError('The locale tag must be a string or an object.'),
        };

        if ($options === null) {
            throw new TypeError('The locale options must not be null.');
        }

        $this->identifier = LocaleIdentifier::parse($tag);

        if (is_array($options) || is_object($options)) {
            $languageOption = self::readOption($options, 'language');
            if ($languageOption->present) {
                $this->identifier->replaceLanguageId(
                    self::toStringValue($languageOption->value),
                    $this->identifier->script,
                    $this->identifier->region,
                    $this->identifier->variants === [] ? null : implode('-', $this->identifier->variants),
                );
            }

            $scriptOption = self::readOption($options, 'script');
            if ($scriptOption->present) {
                $this->identifier->replaceLanguageId(
                    $this->identifier->language,
                    self::toStringValue($scriptOption->value),
                    $this->identifier->region,
                    $this->identifier->variants === [] ? null : implode('-', $this->identifier->variants),
                );
            }

            $regionOption = self::readOption($options, 'region');
            if ($regionOption->present) {
                $this->identifier->replaceLanguageId(
                    $this->identifier->language,
                    $this->identifier->script,
                    self::toStringValue($regionOption->value),
                    $this->identifier->variants === [] ? null : implode('-', $this->identifier->variants),
                );
            }

            $variantsOption = self::readOption($options, 'variants');
            if ($variantsOption->present) {
                $this->identifier->replaceLanguageId(
                    $this->identifier->language,
                    $this->identifier->script,
                    $this->identifier->region,
                    self::toStringValue($variantsOption->value),
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
        }
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

    /**
     * @param array<array-key, mixed>|object $options
     */
    private static function readOption(array|object $options, string $name): OptionValue
    {
        if ($options instanceof OptionBag) {
            return self::optionValue($options->get($name));
        }

        if (is_array($options)) {
            return array_key_exists($name, $options) ? self::optionValue($options[$name]) : OptionValue::missing();
        }

        $properties = get_object_vars($options);

        return array_key_exists($name, $properties) ? self::optionValue($properties[$name]) : OptionValue::missing();
    }

    private static function initializedTag(self $locale): string
    {
        if (!$locale->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return $locale->identifier->toString();
    }

    private static function optionValue(mixed $value): OptionValue
    {
        return $value === UndefinedValue::Value ? OptionValue::missing() : OptionValue::present($value);
    }

    private static function toStringValue(mixed $value): string
    {
        if ($value instanceof SymbolValue) {
            throw new TypeError('A Symbol value cannot be converted to a string.');
        }

        if ($value === UndefinedValue::Value) {
            return 'undefined';
        }

        if (is_string($value) || is_int($value) || is_float($value)) {
            if ($value === -0.0) {
                return '0';
            }

            return (string) $value;
        }

        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value instanceof ObjectValue) {
            return self::toStringValue(self::toPrimitive($value));
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        throw new TypeError('Locale option cannot be converted to a string.');
    }

    private static function toPrimitive(ObjectValue $value): mixed
    {
        $exoticResult = self::callExoticToPrimitive($value->get('@@toPrimitive'));
        if ($exoticResult->present) {
            return $exoticResult->value;
        }

        foreach (['toString', 'valueOf'] as $name) {
            $ordinaryResult = self::callOrdinaryToPrimitiveMethod($value->get($name));
            if ($ordinaryResult->present) {
                return $ordinaryResult->value;
            }
        }

        throw new TypeError('Locale object cannot be converted to a primitive value.');
    }

    private static function callExoticToPrimitive(mixed $method): OptionValue
    {
        if ($method === UndefinedValue::Value) {
            return OptionValue::missing();
        }
        if (!is_callable($method)) {
            throw new TypeError('Symbol.toPrimitive must be callable.');
        }

        return OptionValue::present(self::requirePrimitive(
            $method('string'),
            'Symbol.toPrimitive must return a primitive value.',
        ));
    }

    private static function callOrdinaryToPrimitiveMethod(mixed $method): OptionValue
    {
        if (!is_callable($method)) {
            return OptionValue::missing();
        }

        return self::primitiveOption($method());
    }

    private static function primitiveOption(mixed $value): OptionValue
    {
        return self::isPrimitive($value) ? OptionValue::present($value) : OptionValue::missing();
    }

    private static function requirePrimitive(mixed $value, string $message): mixed
    {
        if (!self::isPrimitive($value)) {
            throw new TypeError($message);
        }

        return $value;
    }

    private static function isPrimitive(mixed $value): bool
    {
        return (
            $value === null
            || is_scalar($value)
            || $value === UndefinedValue::Value
            || $value instanceof SymbolValue
        );
    }

    /** @param array<array-key, mixed>|object $options */
    private static function applyStringKeywordOption(
        LocaleIdentifier $identifier,
        array|object $options,
        string $optionName,
        string $key,
    ): void {
        $option = self::readOption($options, $optionName);
        if (!$option->present) {
            return;
        }

        $value = self::toStringValue($option->value);
        if (preg_match('/\A[A-Za-z0-9]{3,8}(?:-[A-Za-z0-9]{3,8})*\z/', $value) !== 1) {
            throw new RangeError(sprintf('Invalid %s option: "%s".', $optionName, $value));
        }
        $identifier->setKeyword($key, $value);
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
    ): void {
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
        if (is_float($value) && is_nan($value)) {
            return false;
        }

        return match (true) {
            $value === null, $value === UndefinedValue::Value => false,
            is_bool($value) => $value,
            is_int($value), is_float($value) => $value != 0,
            is_string($value) => $value !== '',
            default => true,
        };
    }
}
