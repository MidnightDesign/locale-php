<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class CollationDataImporter
{
    /**
     * @param array<string, string> $localeSources canonical locale => CLDR collation XML
     * @return array{root: list<string>, locales: array<string, list<string>>}
     */
    public static function project(array $localeSources, string $bcp47Source, string $supplementalSource): array
    {
        $aliases = self::typeAliases(self::xml($bcp47Source));
        $parents = self::parents(self::xml($supplementalSource));
        $localTypes = [];
        foreach ($localeSources as $locale => $source) {
            $localTypes[$locale] = self::canonicalTypes(self::xml($source), $aliases);
        }
        if (!isset($localTypes['root'])) {
            throw new \UnexpectedValueException('The pinned CLDR collation input is missing the root locale.');
        }

        $resolved = [];
        $resolving = [];
        foreach (array_keys($localTypes) as $locale) {
            self::resolveTypes($locale, $localTypes, $parents, $resolved, $resolving);
        }

        $root = $resolved['root'];
        $locales = [];
        foreach ($resolved as $locale => $types) {
            if ($locale !== 'root' && $types !== $root) {
                $locales[$locale] = $types;
            }
        }
        ksort($locales, SORT_STRING);

        return ['root' => $root, 'locales' => $locales];
    }

    private static function xml(string $source): \SimpleXMLElement
    {
        $document = simplexml_load_string($source, \SimpleXMLElement::class, LIBXML_NONET);
        if ($document === false) {
            throw new \UnexpectedValueException('The pinned CLDR collation input contains malformed XML.');
        }

        return $document;
    }

    /** @param array<string, string> $aliases
     * @return list<string>
     */
    private static function canonicalTypes(\SimpleXMLElement $document, array $aliases): array
    {
        $types = [];
        foreach ($document->xpath('//collation[@type]') ?: [] as $collation) {
            $type = (string) $collation['type'];
            if ($type === 'standard' || $type === 'search' || str_starts_with($type, 'private-')) {
                continue;
            }
            $types[] = $aliases[$type] ?? $type;
        }
        $types = array_values(array_unique($types));
        sort($types, SORT_STRING);

        return $types;
    }

    /** @return array<string, string> */
    private static function typeAliases(\SimpleXMLElement $document): array
    {
        $aliases = [];
        foreach ($document->xpath('//key[@name="co"]/type[@name]') ?: [] as $type) {
            $canonical = (string) $type['name'];
            foreach (preg_split('/\s+/', trim((string) $type['alias']), flags: PREG_SPLIT_NO_EMPTY) ?: [] as $alias) {
                $aliases[$alias] = $canonical;
            }
        }

        return $aliases;
    }

    /** @return array<string, string> */
    private static function parents(\SimpleXMLElement $document): array
    {
        $parents = [];
        foreach ($document->xpath(
            '//parentLocales[contains(concat(" ", @component, " "), " collations ")]/parentLocale',
        ) ?: [] as $entry) {
            self::addParents($parents, $entry);
        }

        return $parents;
    }

    /** @param array<string, string> $parents */
    private static function addParents(array &$parents, \SimpleXMLElement $entry): void
    {
        $parent = str_replace('_', '-', (string) $entry['parent']);
        foreach (preg_split('/\s+/', trim((string) $entry['locales']), flags: PREG_SPLIT_NO_EMPTY) ?: [] as $locale) {
            $parents[str_replace('_', '-', $locale)] = $parent;
        }
    }

    /**
     * @param array<string, list<string>> $localTypes
     * @param array<string, string>       $parents
     * @param array<string, list<string>> $resolved
     * @param array<string, true>         $resolving
     * @return list<string>
     */
    private static function resolveTypes(
        string $locale,
        array $localTypes,
        array $parents,
        array &$resolved,
        array &$resolving,
    ): array {
        if (isset($resolved[$locale])) {
            return $resolved[$locale];
        }
        if (isset($resolving[$locale])) {
            throw new \UnexpectedValueException('The pinned CLDR collation input contains a parent cycle.');
        }
        $resolving[$locale] = true;

        if ($locale === 'root') {
            $types = $localTypes['root'];
        } else {
            $separator = strrpos($locale, '-');
            $parent = $parents[$locale] ?? ($separator === false ? 'root' : substr($locale, 0, $separator));
            $types = array_merge(
                self::resolveTypes($parent, $localTypes, $parents, $resolved, $resolving),
                $localTypes[$locale] ?? [],
            );
            $types = array_values(array_unique($types));
            sort($types, SORT_STRING);
        }

        unset($resolving[$locale]);

        return $resolved[$locale] = $types;
    }
}
