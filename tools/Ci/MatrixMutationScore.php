<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class MatrixMutationScore
{
    private const FAILURE_FIELDS = [
        'escaped' => 'escapedCount',
        'uncovered' => 'notCoveredCount',
        'errored' => 'errorCount',
        'syntaxErrors' => 'syntaxErrorCount',
        'skipped' => 'skippedCount',
        'ignored' => 'ignoredCount',
        'timedOut' => 'timeOutCount',
    ];

    /**
     * @param array<string, array{stats: array<string, int>}> $reports
     * @return array{
     *     format: int,
     *     obligations: int,
     *     killed: int,
     *     score: float,
     *     passing: bool,
     *     failures: array{escaped: int, uncovered: int, errored: int, syntaxErrors: int, skipped: int, ignored: int, timedOut: int},
     *     modes: array<string, array{obligations: int, killed: int, failures: int}>
     * }
     */
    public static function aggregate(array $reports): array
    {
        $modes = array_keys($reports);
        sort($modes);
        if ($modes !== ['absent', 'disabled', 'native']) {
            throw new \RuntimeException('Mutation evidence must contain absent, disabled, and native extension modes.');
        }

        $obligations = 0;
        $killed = 0;
        $failures = [
            'escaped' => 0,
            'uncovered' => 0,
            'errored' => 0,
            'syntaxErrors' => 0,
            'skipped' => 0,
            'ignored' => 0,
            'timedOut' => 0,
        ];
        $modeEvidence = [];

        foreach ($reports as $mode => $report) {
            $stats = $report['stats'];
            $modeObligations = self::stat($stats, 'totalMutantsCount');
            $modeKilled = self::stat($stats, 'killedCount')
                + self::stat($stats, 'killedByStaticAnalysisCount');
            $modeFailures = 0;
            foreach (self::FAILURE_FIELDS as $name => $field) {
                $count = self::stat($stats, $field);
                $failures[$name] += $count;
                $modeFailures += $count;
            }

            $obligations += $modeObligations;
            $killed += $modeKilled;
            $modeEvidence[$mode] = [
                'obligations' => $modeObligations,
                'killed' => $modeKilled,
                'failures' => $modeFailures,
            ];
        }

        $score = $obligations === 0 ? 0.0 : round($killed / $obligations * 100, 4);

        return [
            'format' => 1,
            'obligations' => $obligations,
            'killed' => $killed,
            'score' => $score,
            'passing' => $obligations > 0 && $killed === $obligations && array_sum($failures) === 0,
            'failures' => $failures,
            'modes' => $modeEvidence,
        ];
    }

    /** @param array<string, int> $stats */
    private static function stat(array $stats, string $name): int
    {
        if (!array_key_exists($name, $stats)) {
            throw new \RuntimeException(sprintf('Mutation report is missing stats.%s.', $name));
        }

        return $stats[$name];
    }
}
