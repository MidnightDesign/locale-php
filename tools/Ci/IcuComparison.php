<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class IcuComparison
{
    /**
     * @param int<1, 7>      $releaseDataSnapshotFirstDay
     * @param int<1, 7>|null $hostIcuFirstDay
     * @param array<string, bool> $hostCapabilities
     * @param array<string, array{operation: string, locale: string, result: mixed}> $fallbackEvidence
     * @return array{locale: string, releaseDataSnapshotFirstDay: int<1, 7>, hostIcuVersion: string|null, hostIcuFirstDay: int<1, 7>|null, result: 'host-icu-unavailable'|'host-matches-release-data-snapshot'|'host-differs-from-release-data-snapshot', semanticAuthority: 'release-data-snapshot', hostCapabilities: array<string, bool>, missingCapabilityFallbacks: array<string, 'release-data-snapshot'>, fallbackEvidence: array<string, array{operation: string, locale: string, result: mixed}>}
     */
    public static function evidence(
        string $locale,
        int $releaseDataSnapshotFirstDay,
        ?string $hostIcuVersion,
        ?int $hostIcuFirstDay,
        array $hostCapabilities,
        array $fallbackEvidence,
    ): array {
        if (($hostIcuVersion === null) !== ($hostIcuFirstDay === null)) {
            throw new \InvalidArgumentException('The host ICU version and result must be recorded together.');
        }

        $result = match (true) {
            $hostIcuFirstDay === null => 'host-icu-unavailable',
            $hostIcuFirstDay === $releaseDataSnapshotFirstDay => 'host-matches-release-data-snapshot',
            default => 'host-differs-from-release-data-snapshot',
        };
        ksort($hostCapabilities);
        $missingCapabilityFallbacks = [];
        foreach ($hostCapabilities as $capability => $available) {
            if (!$available) {
                $missingCapabilityFallbacks[$capability] = 'release-data-snapshot';
            }
        }
        ksort($fallbackEvidence);
        if (array_keys($fallbackEvidence) !== array_keys($missingCapabilityFallbacks)) {
            throw new \InvalidArgumentException('Every missing host capability must include fallback evidence.');
        }

        return [
            'locale' => $locale,
            'releaseDataSnapshotFirstDay' => $releaseDataSnapshotFirstDay,
            'hostIcuVersion' => $hostIcuVersion,
            'hostIcuFirstDay' => $hostIcuFirstDay,
            'result' => $result,
            'semanticAuthority' => 'release-data-snapshot',
            'hostCapabilities' => $hostCapabilities,
            'missingCapabilityFallbacks' => $missingCapabilityFallbacks,
            'fallbackEvidence' => $fallbackEvidence,
        ];
    }
}
