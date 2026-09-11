<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class TimeZoneDataImporter
{
    /**
     * @param list<string>          $tzdbSources
     * @param array<string, string> $pendingRenames replacement identifier to renamed identifier
     * @return array{
     *     zones: list<string>,
     *     links: array<string, string>,
     *     primaryIdentifiers: array<string, string>,
     *     regions: array<string, list<string>>
     * }
     */
    public static function project(
        array $tzdbSources,
        string $zoneTab,
        string $backzone,
        string $cldrTimeZones,
        array $pendingRenames = [],
    ): array {
        $zones = [];
        $links = [];
        foreach ($tzdbSources as $source) {
            foreach (preg_split('/\R/', $source) ?: [] as $line) {
                if (preg_match('/^\s*Zone\s+(\S+)/', $line, $match) === 1) {
                    $zones[$match[1]] = true;
                } elseif (preg_match('/^\s*Link\s+(\S+)\s+(\S+)/', $line, $match) === 1) {
                    $links[$match[2]] = $match[1];
                }
            }
        }

        $regions = [];
        $regionByIdentifier = [];
        foreach (preg_split('/\R/', $zoneTab) ?: [] as $line) {
            if (trim($line) === '' || str_starts_with(ltrim($line), '#')) {
                continue;
            }
            $columns = preg_split('/\s+/', trim($line));
            if ($columns === false || count($columns) < 3 || preg_match('/^[A-Z]{2}$/D', $columns[0]) !== 1) {
                throw new \UnexpectedValueException('The pinned zone.tab input contains a malformed row.');
            }
            $region = $columns[0];
            $identifier = $columns[2];
            $regions[$region][] = $identifier;
            $regionByIdentifier[$identifier] = $region;
        }

        foreach (self::cldrIdentifierRegions($cldrTimeZones) as $identifier => $region) {
            $regionByIdentifier[$identifier] ??= $region;
        }

        $backzoneLinks = [];
        foreach (preg_split('/\R/', $backzone) ?: [] as $line) {
            if (preg_match('/^\s*(?:#PACKRATLIST\s+zone\.tab\s+)?Link\s+(\S+)\s+(\S+)/', $line, $match) === 1) {
                $backzoneLinks[$match[2]] = $match[1];
            }
        }

        $zoneTabIdentifiers = array_fill_keys(array_merge(...array_values($regions)), true);
        $primaryIdentifiers = [];
        foreach (array_keys($zones + $links) as $identifier) {
            $primary = $identifier;
            if (isset($links[$identifier]) && !isset($zoneTabIdentifiers[$identifier])) {
                $zone = self::resolveLink($identifier, $links, $zones);
                if (str_starts_with($zone, 'Etc/')) {
                    $primary = $zone;
                } else {
                    $identifierRegion = $regionByIdentifier[$identifier] ?? null;
                    $zoneRegion = $regionByIdentifier[$zone] ?? null;
                    if ($identifierRegion !== null && $identifierRegion === $zoneRegion) {
                        $primary = $zone;
                    } elseif ($identifierRegion !== null && count($regions[$identifierRegion] ?? []) === 1) {
                        $primary = $regions[$identifierRegion][0];
                    } elseif (isset($backzoneLinks[$identifier])) {
                        $primary = $backzoneLinks[$identifier];
                    } else {
                        $primary = $zone;
                    }
                }
            }

            if (in_array($primary, ['Etc/UTC', 'Etc/GMT', 'GMT'], true)) {
                $primary = 'UTC';
            }
            $primaryIdentifiers[$identifier] = $pendingRenames[$primary] ?? $primary;
        }

        foreach ($regions as &$identifiers) {
            $identifiers = array_values(array_unique(array_map(
                static fn(string $identifier): string => $primaryIdentifiers[$identifier],
                $identifiers,
            )));
            sort($identifiers, SORT_STRING);
        }
        unset($identifiers);

        $zoneList = array_keys($zones);
        sort($zoneList, SORT_STRING);
        ksort($links, SORT_STRING);
        ksort($primaryIdentifiers, SORT_STRING);
        ksort($regions, SORT_STRING);

        return [
            'zones' => $zoneList,
            'links' => $links,
            'primaryIdentifiers' => $primaryIdentifiers,
            'regions' => $regions,
        ];
    }

    /**
     * @param array<string, string> $links
     * @param array<string, bool>   $zones
     */
    private static function resolveLink(string $identifier, array $links, array $zones): string
    {
        $seen = [];
        while (isset($links[$identifier])) {
            if (isset($seen[$identifier])) {
                throw new \UnexpectedValueException('The pinned tzdb input contains a Link cycle.');
            }
            $seen[$identifier] = true;
            $identifier = $links[$identifier];
        }
        if (!isset($zones[$identifier])) {
            throw new \UnexpectedValueException('The pinned tzdb input contains a Link without a Zone target.');
        }

        return $identifier;
    }

    /** @return array<string, string> */
    private static function cldrIdentifierRegions(string $source): array
    {
        preg_match_all('/<type\s+([^>]+?)\/>/', $source, $matches, PREG_SET_ORDER);
        $regions = [];
        foreach ($matches as $match) {
            $attributes = self::xmlAttributes($match[1]);
            $name = strtolower($attributes['name'] ?? '');
            $region = strtoupper($attributes['region'] ?? substr($name, 0, 2));
            if (preg_match('/^[A-Z]{2}$/D', $region) !== 1) {
                continue;
            }
            foreach (preg_split('/\s+/', trim($attributes['alias'] ?? ''), flags: PREG_SPLIT_NO_EMPTY)
                ?: [] as $alias) {
                $regions[$alias] = $region;
            }
            if (isset($attributes['iana'])) {
                $regions[$attributes['iana']] = $region;
            }
        }

        return $regions;
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
