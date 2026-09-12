<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class IcuComparison
{
    /**
     * @param int<1, 7>      $releaseSnapshotFirstDay
     * @param int<1, 7>|null $hostIcuFirstDay
     * @return array{locale: string, releaseSnapshotFirstDay: int<1, 7>, hostIcuVersion: string|null, hostIcuFirstDay: int<1, 7>|null, result: 'host-icu-unavailable'|'host-matches-release-snapshot'|'host-differs-from-release-snapshot', semanticAuthority: 'release-data-snapshot'}
     */
    public static function evidence(
        string $locale,
        int $releaseSnapshotFirstDay,
        ?string $hostIcuVersion,
        ?int $hostIcuFirstDay,
    ): array {
        if (($hostIcuVersion === null) !== ($hostIcuFirstDay === null)) {
            throw new \InvalidArgumentException('The host ICU version and result must be recorded together.');
        }

        $result = match (true) {
            $hostIcuFirstDay === null => 'host-icu-unavailable',
            $hostIcuFirstDay === $releaseSnapshotFirstDay => 'host-matches-release-snapshot',
            default => 'host-differs-from-release-snapshot',
        };

        return [
            'locale' => $locale,
            'releaseSnapshotFirstDay' => $releaseSnapshotFirstDay,
            'hostIcuVersion' => $hostIcuVersion,
            'hostIcuFirstDay' => $hostIcuFirstDay,
            'result' => $result,
            'semanticAuthority' => 'release-data-snapshot',
        ];
    }
}
