<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class NumberingSystemDataImporter
{
    /**
     * @param array<string, string> $localeSources CLDR locale identifier to common/main XML.
     * @return array{defaults: array<string, string>, aliases: array<string, string>, inheritance: array<string, string>}
     */
    public static function project(
        array $localeSources,
        string $supplementalData,
        ?string $numberingSystems = null,
    ): array {
        $explicitDefaults = [];
        foreach ($localeSources as $locale => $source) {
            if (preg_match('/<defaultNumberingSystem>([^<]+)<\/defaultNumberingSystem>/', $source, $match) === 1) {
                $explicitDefaults[$locale] = strtolower($match[1]);
            }
        }
        if ($numberingSystems !== null) {
            preg_match_all('/<numberingSystem\s+[^>]*\bid="([^"]+)"[^>]*\/>/', $numberingSystems, $matches);
            $registered = array_fill_keys(array_map('strtolower', $matches[1]), true);
            foreach ($explicitDefaults as $locale => $default) {
                if (!isset($registered[$default])) {
                    throw new \UnexpectedValueException(sprintf(
                        'CLDR locale %s has an unregistered default numbering system %s.',
                        $locale,
                        $default,
                    ));
                }
            }
        }

        $explicitParents = [];
        preg_match_all('/<parentLocale\s+([^>]+?)\/>/', $supplementalData, $parentMatches, PREG_SET_ORDER);
        foreach ($parentMatches as $parentMatch) {
            $attributes = self::xmlAttributes($parentMatch[1]);
            if (isset($attributes['component']) && $attributes['component'] !== 'numbers') {
                continue;
            }
            $parent = $attributes['parent'] ?? '';
            foreach (preg_split('/\s+/', $attributes['locales'] ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [] as $locale) {
                $explicitParents[$locale] = $parent;
            }
        }

        $defaults = [];
        $aliases = [];
        $inheritance = [];
        foreach (array_keys($localeSources) as $locale) {
            [$default, $sourceLocale] = self::resolveDefault(
                $locale,
                $localeSources,
                $explicitDefaults,
                $explicitParents,
            );
            $canonicalLocale = self::canonicalizeLocale($locale);
            $defaults[$canonicalLocale] = $default;
            if ($sourceLocale !== $locale) {
                $inheritance[$canonicalLocale] = self::canonicalizeLocale($sourceLocale);
            }
        }

        foreach (array_keys($localeSources) as $locale) {
            $parts = explode('_', $locale);
            if (
                count($parts) >= 3
                && strlen($parts[1]) === 4
                && (strlen($parts[2]) === 2 || strlen($parts[2]) === 3 && ctype_digit($parts[2]))
            ) {
                $scriptless = self::canonicalizeLocale($parts[0] . '_' . $parts[2]);
                if (!isset($defaults[$scriptless])) {
                    $aliases[$scriptless] = self::canonicalizeLocale($locale);
                }
            }
        }

        ksort($defaults, SORT_STRING);
        ksort($aliases, SORT_STRING);
        ksort($inheritance, SORT_STRING);

        return ['defaults' => $defaults, 'aliases' => $aliases, 'inheritance' => $inheritance];
    }

    /**
     * @param array<string, string> $localeSources
     * @param array<string, string> $explicitDefaults
     * @param array<string, string> $explicitParents
     * @param array<string, true>   $seen
     * @return array{string, string}
     */
    private static function resolveDefault(
        string $locale,
        array $localeSources,
        array $explicitDefaults,
        array $explicitParents,
        array $seen = [],
    ): array {
        if (isset($seen[$locale])) {
            throw new \UnexpectedValueException(sprintf('CLDR locale inheritance contains a cycle at %s.', $locale));
        }
        if (isset($explicitDefaults[$locale])) {
            return [$explicitDefaults[$locale], $locale];
        }

        $seen[$locale] = true;
        $parent = $explicitParents[$locale] ?? self::structuralParent($locale);
        if (!isset($localeSources[$parent])) {
            $parent = 'root';
        }
        if ($locale === 'root' || !isset($localeSources[$parent])) {
            return ['latn', 'root'];
        }

        return self::resolveDefault($parent, $localeSources, $explicitDefaults, $explicitParents, $seen);
    }

    private static function structuralParent(string $locale): string
    {
        $position = strrpos($locale, '_');

        return $position === false ? 'root' : substr($locale, 0, $position);
    }

    private static function canonicalizeLocale(string $locale): string
    {
        if ($locale === 'root') {
            return $locale;
        }

        $parts = explode('_', $locale);
        $parts[0] = strtolower($parts[0]);
        foreach (array_slice($parts, 1, null, true) as $index => $part) {
            $parts[$index] = match (true) {
                strlen($part) === 4 => ucfirst(strtolower($part)),
                strlen($part) === 2 || strlen($part) === 3 && ctype_digit($part) => strtoupper($part),
                default => strtolower($part),
            };
        }

        return implode('-', $parts);
    }

    /** @return array<string, string> */
    private static function xmlAttributes(string $source): array
    {
        preg_match_all('/([A-Za-z][A-Za-z0-9]*)="([^"]*)"/', $source, $matches, PREG_SET_ORDER);
        $attributes = [];
        foreach ($matches as $match) {
            $attributes[$match[1]] = html_entity_decode($match[2], ENT_QUOTES | ENT_XML1);
        }

        return $attributes;
    }
}
