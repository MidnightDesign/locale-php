<?php

declare(strict_types=1);

namespace Midnight\Intl\Spec;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Data\LocaleAliases;
use Midnight\Intl\Internal\Test262\OptionBag;

/**
 * @property-read string $baseName
 * @property-read string $language
 * @property-read string|null $script
 * @property-read string|null $region
 */
#[\AllowDynamicProperties]
class Locale
{
    private string $localeBaseName;

    private string $localeLanguage;

    private ?string $localeScript;

    private ?string $localeRegion;

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

        if (!preg_match('/^(?<language>[A-Za-z]{2,3}|[A-Za-z]{5,8})(?:-(?<script>[A-Za-z]{4}))?(?:-(?<region>[A-Za-z]{2}|[0-9]{3}))?$/D', $tag, $matches)) {
            throw new RangeError(sprintf('Invalid locale identifier: "%s".', $tag));
        }

        $this->localeLanguage = self::normalizeLanguage($matches['language']);
        $this->localeScript = ($matches['script'] ?? '') !== ''
            ? self::normalizeScript($matches['script'])
            : null;
        $this->localeRegion = ($matches['region'] ?? '') !== ''
            ? self::normalizeRegion($matches['region'])
            : null;

        if (is_array($options) || is_object($options)) {
            $language = self::readOption($options, 'language');
            if ($language[0]) {
                $this->localeLanguage = self::normalizeLanguage(self::toStringValue($language[1]));
            }

            $script = self::readOption($options, 'script');
            if ($script[0]) {
                $this->localeScript = self::normalizeScript(self::toStringValue($script[1]));
            }

            $region = self::readOption($options, 'region');
            if ($region[0]) {
                $this->localeRegion = self::normalizeRegion(self::toStringValue($region[1]));
            }
        }

        $this->applyAliases();

        $this->localeBaseName = implode('-', array_filter([
            $this->localeLanguage,
            $this->localeScript,
            $this->localeRegion,
        ], static fn (?string $subtag): bool => $subtag !== null));
        $this->initialized = true;
    }

    public function __get(string $name): mixed
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return match ($name) {
            'baseName' => $this->localeBaseName,
            'language' => $this->localeLanguage,
            'script' => $this->localeScript,
            'region' => $this->localeRegion,
            default => throw new \Error(sprintf('Undefined property %s::$%s.', self::class, $name)),
        };
    }

    public function __set(string $name, mixed $value): void
    {
        if (in_array($name, [
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
        ], true)) {
            throw new TypeError(sprintf('Locale property "%s" is read-only.', $name));
        }

        $this->{$name} = $value;
    }

    public function __isset(string $name): bool
    {
        return in_array($name, ['baseName', 'language', 'script', 'region'], true)
            && $this->__get($name) !== null;
    }

    public function toString(): string
    {
        if (!$this->initialized) {
            throw new TypeError('Locale is not initialized.');
        }

        return $this->localeBaseName;
    }

    /**
     * @param array<array-key, mixed>|object $options
     * @return array{bool, mixed}
     */
    private static function readOption(array|object $options, string $name): array
    {
        if ($options instanceof OptionBag) {
            return $options->has($name)
                ? [true, $options->get($name)]
                : [false, null];
        }

        if (is_array($options)) {
            return array_key_exists($name, $options)
                ? [true, $options[$name]]
                : [false, null];
        }

        return property_exists($options, $name)
            ? [true, $options->{$name}]
            : [false, null];
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

    private static function normalizeLanguage(string $language): string
    {
        if (!preg_match('/^(?:[A-Za-z]{2,3}|[A-Za-z]{5,8})$/D', $language)) {
            throw new RangeError(sprintf('Invalid language subtag: "%s".', $language));
        }

        return strtolower($language);
    }

    private static function normalizeScript(string $script): string
    {
        if (!preg_match('/^[A-Za-z]{4}$/D', $script)) {
            throw new RangeError(sprintf('Invalid script subtag: "%s".', $script));
        }

        return ucfirst(strtolower($script));
    }

    private static function normalizeRegion(string $region): string
    {
        if (!preg_match('/^(?:[A-Za-z]{2}|[0-9]{3})$/D', $region)) {
            throw new RangeError(sprintf('Invalid region subtag: "%s".', $region));
        }

        return strtoupper($region);
    }

    private function applyAliases(): void
    {
        $replacement = LocaleAliases::LANGUAGE[$this->localeLanguage] ?? null;
        if ($replacement !== null) {
            $parts = explode('-', $replacement);
            $this->localeLanguage = self::normalizeLanguage($parts[0]);

            foreach (array_slice($parts, 1) as $part) {
                if ($this->localeScript === null && preg_match('/^[A-Za-z]{4}$/D', $part)) {
                    $this->localeScript = self::normalizeScript($part);
                } elseif ($this->localeRegion === null) {
                    $this->localeRegion = self::normalizeRegion($part);
                }
            }
        }

        if ($this->localeScript !== null) {
            $this->localeScript = LocaleAliases::SCRIPT[$this->localeScript] ?? $this->localeScript;
        }
        if ($this->localeRegion !== null) {
            $this->localeRegion = LocaleAliases::REGION[$this->localeRegion] ?? $this->localeRegion;
        }
    }
}
