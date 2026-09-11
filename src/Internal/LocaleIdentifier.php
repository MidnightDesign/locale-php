<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Internal\Data\LocaleAliases;

final class LocaleIdentifier
{
    /**
     * @param list<string> $variants
     * @param array<string, list<string>> $extensions
     */
    private function __construct(
        public string $language,
        public ?string $script,
        public ?string $region,
        public array $variants,
        private array $extensions,
    ) {}

    public static function parse(string $tag): self
    {
        LocaleAliases::assertIntegrity();

        if ($tag === '' || preg_match('/^[A-Za-z0-9-]+$/D', $tag) !== 1) {
            throw self::invalid($tag);
        }

        $subtags = explode('-', strtolower($tag));
        if (in_array('', $subtags, true)) {
            throw self::invalid($tag);
        }

        $offset = 0;
        $languageId = self::parseLanguageId($subtags, $offset, $tag);
        [$language, $script, $region, $variants] = $languageId;
        /** @var array<string, list<string>> $extensions */
        $extensions = [];
        while ($offset < count($subtags)) {
            $singleton = $subtags[$offset++];
            if (strlen($singleton) !== 1 || !ctype_alnum($singleton) || isset($extensions[$singleton])) {
                throw self::invalid($tag);
            }

            if ($singleton === 'x') {
                $private = array_slice($subtags, $offset);
                $offset = count($subtags);
                if ($private === [] || !self::allMatch($private, '/^[a-z0-9]{1,8}$/D')) {
                    throw self::invalid($tag);
                }
                $extensions[$singleton] = $private;
                continue;
            }

            $value = self::takeUntilSingleton($subtags, $offset);
            if ($value === []) {
                throw self::invalid($tag);
            }

            $extensions[$singleton] = match ($singleton) {
                'u' => self::canonicalizeUnicodeExtension($value, $tag),
                't' => self::canonicalizeTransformedExtension($value, $tag),
                default => self::allMatch($value, '/^[a-z0-9]{2,8}$/D') ? $value : throw self::invalid($tag),
            };
        }

        $identifier = new self($language, $script, $region, $variants, $extensions);
        $identifier->canonicalizeLanguageId();
        ksort($identifier->extensions, SORT_STRING);

        return $identifier;
    }

    public function replaceLanguageId(string $language, ?string $script, ?string $region, ?string $variants): void
    {
        if (preg_match('/^(?:[a-z]{2,3}|[a-z]{5,8})$/iD', $language) !== 1) {
            throw new RangeError(sprintf('Invalid language subtag: "%s".', $language));
        }
        if ($script !== null && preg_match('/^[a-z]{4}$/iD', $script) !== 1) {
            throw new RangeError(sprintf('Invalid script subtag: "%s".', $script));
        }
        if ($region !== null && preg_match('/^(?:[a-z]{2}|[0-9]{3})$/iD', $region) !== 1) {
            throw new RangeError(sprintf('Invalid region subtag: "%s".', $region));
        }

        $variantList = $variants === null ? [] : explode('-', strtolower($variants));
        if (
            $variants !== null
            && (
                $variants === ''
                || !self::allMatch($variantList, '/^(?:[a-z0-9]{5,8}|[0-9][a-z0-9]{3})$/D')
                || count(array_unique($variantList)) !== count($variantList)
            )
        ) {
            throw new RangeError(sprintf('Invalid variants value: "%s".', $variants));
        }

        $this->language = strtolower($language);
        $this->script = $script === null ? null : ucfirst(strtolower($script));
        $this->region = $region === null ? null : strtoupper($region);
        $this->variants = $variantList;
        $this->canonicalizeLanguageId();
    }

    public function keyword(string $key): ?string
    {
        $keywords = $this->unicodeKeywords();

        return array_key_exists($key, $keywords) ? implode('-', $keywords[$key]) : null;
    }

    public function setKeyword(string $key, string $value): void
    {
        $attributes = $this->unicodeAttributes();
        $keywords = $this->unicodeKeywords();
        $canonicalValue = self::canonicalizeType($key, strtolower($value));
        $keywords[$key] = $canonicalValue === '' ? [] : explode('-', $canonicalValue);
        ksort($keywords, SORT_STRING);

        $this->extensions['u'] = self::flattenUnicodeExtension($attributes, $keywords);
        ksort($this->extensions, SORT_STRING);
    }

    public function baseName(): string
    {
        $parts = [$this->language];
        if ($this->script !== null) {
            $parts[] = $this->script;
        }
        if ($this->region !== null) {
            $parts[] = $this->region;
        }
        array_push($parts, ...$this->variants);

        return implode('-', $parts);
    }

    public function toString(): string
    {
        $parts = [$this->baseName()];
        foreach ($this->extensions as $singleton => $value) {
            $parts[] = $singleton;
            array_push($parts, ...$value);
        }

        return implode('-', $parts);
    }

    /**
     * @param list<string> $subtags
     * @return array{string, ?string, ?string, list<string>}
     */
    private static function parseLanguageId(array $subtags, int &$offset, string $tag): array
    {
        $language = $subtags[$offset] ?? '';
        if (preg_match('/^(?:[a-z]{2,3}|[a-z]{5,8})$/D', $language) !== 1) {
            throw self::invalid($tag);
        }
        ++$offset;

        $script = null;
        if (isset($subtags[$offset]) && preg_match('/^[a-z]{4}$/D', $subtags[$offset]) === 1) {
            $script = ucfirst($subtags[$offset++]);
        }

        $region = null;
        if (isset($subtags[$offset]) && preg_match('/^(?:[a-z]{2}|[0-9]{3})$/D', $subtags[$offset]) === 1) {
            $region = strtoupper($subtags[$offset++]);
        }

        $variants = [];
        while (
            isset($subtags[$offset])
            && preg_match('/^(?:[a-z0-9]{5,8}|[0-9][a-z0-9]{3})$/D', $subtags[$offset]) === 1
        ) {
            $variants[] = $subtags[$offset++];
        }
        if (count(array_unique($variants)) !== count($variants)) {
            throw self::invalid($tag);
        }

        return [$language, $script, $region, $variants];
    }

    /**
     * @param list<string> $subtags
     * @return list<string>
     */
    private static function takeUntilSingleton(array $subtags, int &$offset): array
    {
        $value = [];
        while (isset($subtags[$offset]) && strlen($subtags[$offset]) !== 1) {
            $value[] = $subtags[$offset++];
        }

        return $value;
    }

    /**
     * @param list<string> $subtags
     * @return list<string>
     */
    private static function canonicalizeUnicodeExtension(array $subtags, string $tag): array
    {
        $offset = 0;
        /** @var array<string, string> $attributes */
        $attributes = [];
        while (isset($subtags[$offset]) && preg_match('/^[a-z0-9]{3,8}$/D', $subtags[$offset]) === 1) {
            $attributes[$subtags[$offset]] ??= $subtags[$offset];
            ++$offset;
        }

        /** @var array<string, list<string>> $keywords */
        $keywords = [];
        while (isset($subtags[$offset])) {
            $key = $subtags[$offset++];
            if (preg_match('/^[a-z0-9][a-z]$/D', $key) !== 1) {
                throw self::invalid($tag);
            }
            /** @var list<string> $type */
            $type = [];
            while (isset($subtags[$offset]) && preg_match('/^[a-z0-9]{3,8}$/D', $subtags[$offset]) === 1) {
                $type[] = $subtags[$offset++];
            }
            if (!isset($keywords[$key])) {
                $canonicalType = self::canonicalizeType($key, implode('-', $type));
                $keywords[$key] = $canonicalType === '' ? [] : explode('-', $canonicalType);
            }
        }

        ksort($attributes, SORT_STRING);
        ksort($keywords, SORT_STRING);
        return self::flattenUnicodeExtension(array_values($attributes), $keywords);
    }

    /**
     * @param list<string> $subtags
     * @return list<string>
     */
    private static function canonicalizeTransformedExtension(array $subtags, string $tag): array
    {
        $offset = 0;
        $result = [];
        if (preg_match('/^(?:[a-z]{2,3}|[a-z]{5,8})$/D', $subtags[0]) === 1) {
            $languageIdParts = self::parseLanguageId($subtags, $offset, $tag);
            [$language, $script, $region, $variants] = $languageIdParts;
            $languageId = new self($language, $script, $region, $variants, []);
            $languageId->canonicalizeLanguageId();
            $result = explode('-', $languageId->baseName());
        }

        /** @var array<string, list<string>> $fields */
        $fields = [];
        while (isset($subtags[$offset])) {
            $key = $subtags[$offset++];
            if (preg_match('/^[a-z][0-9]$/D', $key) !== 1) {
                throw self::invalid($tag);
            }
            $value = [];
            while (isset($subtags[$offset]) && preg_match('/^[a-z0-9]{3,8}$/D', $subtags[$offset]) === 1) {
                $value[] = $subtags[$offset++];
            }
            if ($value === []) {
                throw self::invalid($tag);
            }
            $fields[$key] ??= $value;
        }
        if ($result === [] && $fields === []) {
            throw self::invalid($tag);
        }
        ksort($fields, SORT_STRING);
        foreach ($fields as $key => $value) {
            $result[] = $key;
            array_push($result, ...$value);
        }

        return $result;
    }

    private function canonicalizeLanguageId(): void
    {
        $replacement = LocaleAliases::LANGUAGE[$this->language] ?? null;
        if ($replacement !== null) {
            $parts = explode('-', $replacement);
            $this->language = strtolower(array_shift($parts));
            foreach ($parts as $part) {
                if ($this->script === null && preg_match('/^[A-Za-z]{4}$/D', $part) === 1) {
                    $this->script = ucfirst(strtolower($part));
                } elseif ($this->region === null) {
                    $this->region = strtoupper($part);
                }
            }
        }
        if ($this->script !== null) {
            $this->script = LocaleAliases::SCRIPT[$this->script] ?? $this->script;
        }
        if ($this->region !== null) {
            $alternatives = LocaleAliases::REGION_ALTERNATIVES[$this->region] ?? null;
            if ($alternatives !== null) {
                $likelyKey = strtolower($this->language . ($this->script === null ? '' : '-' . $this->script));
                $likelyRegion =
                    LocaleAliases::LIKELY_REGION[$likelyKey] ?? LocaleAliases::LIKELY_REGION[$this->language] ?? null;
                $this->region = $likelyRegion !== null && in_array($likelyRegion, $alternatives, true)
                    ? $likelyRegion
                    : $alternatives[0];
            } else {
                $this->region = LocaleAliases::REGION[$this->region] ?? $this->region;
            }
        }

        $variantAliases = LocaleAliases::VARIANT;
        /** @var array<string, string> $canonicalVariants */
        $canonicalVariants = [];
        foreach ($this->variants as $variant) {
            foreach (explode('-', $variantAliases[$variant] ?? $variant) as $replacementVariant) {
                $canonicalVariants[$replacementVariant] ??= $replacementVariant;
            }
        }
        ksort($canonicalVariants, SORT_STRING);
        $this->variants = array_values($canonicalVariants);
    }

    /** @return list<string> */
    private function unicodeAttributes(): array
    {
        /** @var list<string> $attributes */
        $attributes = [];
        foreach ($this->extensions['u'] ?? [] as $subtag) {
            if (preg_match('/^[a-z0-9][a-z]$/D', $subtag) === 1) {
                break;
            }
            $attributes[] = $subtag;
        }

        return $attributes;
    }

    /** @return array<string, list<string>> */
    private function unicodeKeywords(): array
    {
        /** @var array<string, list<string>> $keywords */
        $keywords = [];
        $extension = $this->extensions['u'] ?? [];
        $offset = count($this->unicodeAttributes());
        while (isset($extension[$offset])) {
            $key = $extension[$offset++];
            $type = [];
            while (isset($extension[$offset]) && strlen($extension[$offset]) >= 3) {
                $type[] = $extension[$offset++];
            }
            $keywords[$key] = $type;
        }

        return $keywords;
    }

    private static function canonicalizeType(string $key, string $type): string
    {
        if ($key === 'sd') {
            $subdivisionAliases = LocaleAliases::SUBDIVISION;

            return $subdivisionAliases[$type] ?? $type;
        }
        $matches = [];
        if ($key === 'rg' && preg_match('/^(?<region>[a-z]{2}|[0-9]{3})(?<suffix>zzzz)$/D', $type, $matches) === 1) {
            $region = LocaleAliases::REGION[strtoupper($matches['region'])] ?? strtoupper($matches['region']);

            return strtolower($region) . $matches['suffix'];
        }

        $typeAliases = LocaleAliases::TYPE;

        $canonical = $typeAliases[$key][$type] ?? $type;

        return $canonical === 'true' ? '' : $canonical;
    }

    /**
     * @param list<string> $attributes
     * @param array<string, list<string>> $keywords
     * @return list<string>
     */
    private static function flattenUnicodeExtension(array $attributes, array $keywords): array
    {
        $extension = $attributes;
        foreach ($keywords as $key => $type) {
            $extension[] = $key;
            array_push($extension, ...$type);
        }

        return $extension;
    }

    /**
     * @param list<string> $values
     * @param non-empty-string $pattern
     */
    private static function allMatch(array $values, string $pattern): bool
    {
        foreach ($values as $value) {
            if (preg_match($pattern, $value) !== 1) {
                return false;
            }
        }

        return true;
    }

    private static function invalid(string $tag): RangeError
    {
        return new RangeError(sprintf('Invalid locale identifier: "%s".', $tag));
    }
}
