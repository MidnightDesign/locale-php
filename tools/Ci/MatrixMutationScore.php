<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class MatrixMutationScore
{
    private const RESULT_FIELDS = [
        'escaped' => ['stat' => 'escapedCount', 'failure' => 'escaped'],
        'uncovered' => ['stat' => 'notCoveredCount', 'failure' => 'uncovered'],
        'errored' => ['stat' => 'errorCount', 'failure' => 'errored'],
        'syntaxErrors' => ['stat' => 'syntaxErrorCount', 'failure' => 'syntaxErrors'],
        'ignored' => ['stat' => 'ignoredCount', 'failure' => 'ignored'],
        'timeouted' => ['stat' => 'timeOutCount', 'failure' => 'timedOut'],
        'killed' => ['stat' => 'killedCount', 'failure' => null],
        'killedByStaticAnalysis' => ['stat' => 'killedByStaticAnalysisCount', 'failure' => 'staticAnalysis'],
    ];

    /**
     * @param array<string, array<string, array<string, mixed>>> $reports
     * @return array{
     *     format: int,
     *     mutants: int,
     *     obligations: int,
     *     killed: int,
     *     score: float,
     *     passing: bool,
     *     failures: array<string, int>,
     *     campaigns: array<string, array{modes: array<string, array{obligations: int, killed: int, failures: int}>}>,
     *     mutations: list<array{id: string, campaign: string, source: string, line: int, mutator: string, original: string, mutated: string, diff: string, applicableModes: list<string>, modes: array<string, string>}>
     * }
     */
    public static function aggregate(array $reports): array
    {
        $campaignNames = array_keys($reports);
        sort($campaignNames);
        $expectedCampaignNames = MutationCampaigns::names();
        sort($expectedCampaignNames);
        if ($campaignNames !== $expectedCampaignNames) {
            throw new \RuntimeException('Mutation evidence must contain porcelain and spec campaigns.');
        }

        $mutations = [];
        $failures = [
            'escaped' => 0,
            'uncovered' => 0,
            'errored' => 0,
            'syntaxErrors' => 0,
            'skipped' => 0,
            'ignored' => 0,
            'timedOut' => 0,
            'staticAnalysis' => 0,
        ];

        foreach ($campaignNames as $campaign) {
            $modeNames = array_keys($reports[$campaign]);
            sort($modeNames);
            if ($modeNames !== MutationCampaigns::extensionModes()) {
                throw new \RuntimeException(sprintf(
                    '%s mutation evidence must contain absent, disabled, and native extension modes.',
                    $campaign,
                ));
            }

            $absentModeIdentities = null;
            /** @var array<string, array{source: string, line: int, mutator: string, original: string, mutated: string, diff: string, modes: array<string, string>}> $campaignMutations */
            $campaignMutations = [];
            foreach (MutationCampaigns::extensionModes() as $mode) {
                $parsed = self::parseReport($campaign, $mode, $reports[$campaign][$mode]);
                $identities = array_keys($parsed['mutations']);
                sort($identities);
                if ($absentModeIdentities === null) {
                    $absentModeIdentities = $identities;
                } elseif ($identities !== $absentModeIdentities) {
                    throw new \RuntimeException(sprintf(
                        '%s/%s mutant set differs from %s/absent.',
                        $campaign,
                        $mode,
                        $campaign,
                    ));
                }

                foreach ($parsed['mutations'] as $identity => $mutation) {
                    $campaignMutations[$identity] ??= $mutation['definition'] + ['modes' => []];
                    $campaignMutations[$identity]['modes'][$mode] = $mutation['result'];
                }
            }

            ksort($campaignMutations);
            foreach ($campaignMutations as $identity => $mutation) {
                $mutations[] = ['id' => $identity, 'campaign' => $campaign] + $mutation;
            }
        }

        /** @var array<string, array{modes: array<string, array{obligations: int, killed: int, failures: int}>}> $campaignEvidence */
        $campaignEvidence = [];
        foreach ($campaignNames as $campaign) {
            foreach (MutationCampaigns::extensionModes() as $mode) {
                $campaignEvidence[$campaign]['modes'][$mode] = [
                    'obligations' => 0,
                    'killed' => 0,
                    'failures' => 0,
                ];
            }
        }

        $obligations = 0;
        $killed = 0;
        foreach ($mutations as &$mutation) {
            $applicableModes = MutationCampaigns::extensionModes();
            $mutation['applicableModes'] = $applicableModes;

            foreach ($applicableModes as $mode) {
                ++$obligations;
                $modeEvidence = $campaignEvidence[$mutation['campaign']]['modes'][$mode];
                ++$modeEvidence['obligations'];
                $result = $mutation['modes'][$mode];
                if ($result === 'killed') {
                    ++$killed;
                    ++$modeEvidence['killed'];
                    $campaignEvidence[$mutation['campaign']]['modes'][$mode] = $modeEvidence;

                    continue;
                }

                $failure = self::RESULT_FIELDS[$result]['failure'];
                if (!is_string($failure)) {
                    throw new \RuntimeException(sprintf(
                        'Mutation %s:%s has an invalid successful result.',
                        $mutation['campaign'],
                        $mutation['id'],
                    ));
                }
                ++$failures[$failure];
                ++$modeEvidence['failures'];
                $campaignEvidence[$mutation['campaign']]['modes'][$mode] = $modeEvidence;
            }
        }
        unset($mutation);
        /** @var list<array{id: string, campaign: string, source: string, line: int, mutator: string, original: string, mutated: string, diff: string, applicableModes: list<string>, modes: array<string, string>}> $mutations */
        $score = $obligations === 0 ? 0.0 : round(($killed / $obligations) * 100, 4);

        return [
            'format' => 2,
            'mutants' => count($mutations),
            'obligations' => $obligations,
            'killed' => $killed,
            'score' => $score,
            'passing' => $obligations > 0 && $killed === $obligations && array_sum($failures) === 0,
            'failures' => $failures,
            'campaigns' => $campaignEvidence,
            'mutations' => $mutations,
        ];
    }

    /**
     * @param array<string, mixed> $report
     * @return array{
     *     mutations: array<string, array{definition: array{source: string, line: int, mutator: string, original: string, mutated: string, diff: string}, result: string}>
     * }
     */
    private static function parseReport(string $campaign, string $mode, array $report): array
    {
        $stats = $report['stats'] ?? null;
        if (!is_array($stats)) {
            throw new \RuntimeException(sprintf('%s/%s mutation report has no stats object.', $campaign, $mode));
        }

        $skipped = self::stat($stats, 'skippedCount');
        $mutations = [];
        $occurrences = [];
        $reported = 0;
        foreach (self::RESULT_FIELDS as $result => $metadata) {
            $rows = $report[$result] ?? null;
            if (!is_array($rows)) {
                throw new \RuntimeException(sprintf('%s/%s mutation report is missing %s.', $campaign, $mode, $result));
            }
            $expected = self::stat($stats, $metadata['stat']);
            if (count($rows) !== $expected) {
                throw new \RuntimeException(sprintf(
                    '%s/%s stats.%s is %d but %d mutants were reported.',
                    $campaign,
                    $mode,
                    $metadata['stat'],
                    $expected,
                    count($rows),
                ));
            }

            foreach ($rows as $row) {
                if (!is_array($row)) {
                    throw new \RuntimeException(sprintf('%s/%s contains an invalid mutant.', $campaign, $mode));
                }
                /** @var array<string, mixed> $row */
                $mutantDefinition = self::parseMutantDefinition($campaign, $row);
                $fingerprint = hash('sha256', json_encode($mutantDefinition, JSON_THROW_ON_ERROR));
                $occurrences[$fingerprint] = ($occurrences[$fingerprint] ?? 0) + 1;
                $identity = sprintf('%s:%d', $fingerprint, $occurrences[$fingerprint]);
                $mutations[$identity] = ['definition' => $mutantDefinition, 'result' => $result];
                ++$reported;
            }
        }

        $total = self::stat($stats, 'totalMutantsCount');
        if (($reported + $skipped) !== $total) {
            throw new \RuntimeException(sprintf(
                '%s/%s stats.totalMutantsCount is %d but %d mutants were reported.',
                $campaign,
                $mode,
                $total,
                $reported,
            ));
        }
        if ($skipped !== 0) {
            throw new \RuntimeException(sprintf(
                '%s/%s contains unattributable skipped obligations.',
                $campaign,
                $mode,
            ));
        }

        return ['mutations' => $mutations];
    }

    /**
     * @param array<string, mixed> $row
     * @return array{source: string, line: int, mutator: string, original: string, mutated: string, diff: string}
     */
    private static function parseMutantDefinition(string $campaign, array $row): array
    {
        $mutator = $row['mutator'] ?? null;
        if (!is_array($mutator)) {
            throw new \RuntimeException(sprintf('%s campaign contains a mutant without mutator metadata.', $campaign));
        }
        $mutatorName = $mutator['mutatorName'] ?? null;
        $original = $mutator['originalSourceCode'] ?? null;
        $mutated = $mutator['mutatedSourceCode'] ?? null;
        $originalFilePath = $mutator['originalFilePath'] ?? null;
        $line = $mutator['originalStartLine'] ?? null;
        if (!is_string($mutatorName)) {
            throw new \RuntimeException(sprintf('%s campaign contains invalid mutator.mutatorName.', $campaign));
        }
        if (!is_string($original)) {
            throw new \RuntimeException(sprintf('%s campaign contains invalid mutator.originalSourceCode.', $campaign));
        }
        if (!is_string($mutated)) {
            throw new \RuntimeException(sprintf('%s campaign contains invalid mutator.mutatedSourceCode.', $campaign));
        }
        if (!is_string($originalFilePath)) {
            throw new \RuntimeException(sprintf('%s campaign contains invalid mutator.originalFilePath.', $campaign));
        }
        if (!is_int($line)) {
            throw new \RuntimeException(sprintf('%s campaign contains invalid mutator.originalStartLine.', $campaign));
        }
        $diff = $row['diff'] ?? null;
        if (!is_string($diff)) {
            throw new \RuntimeException(sprintf('%s campaign contains a mutant without a diff.', $campaign));
        }

        $file = self::sourcePath($originalFilePath);
        $expectedCampaign = MutationCampaigns::forSource($file);
        if ($expectedCampaign === null) {
            throw new \RuntimeException(sprintf('%s is not a hand-written mutation target.', $file));
        }
        if ($campaign !== $expectedCampaign) {
            throw new \RuntimeException(sprintf('%s campaign contains non-%s source %s.', $campaign, $campaign, $file));
        }

        return [
            'source' => $file,
            'line' => $line,
            'mutator' => $mutatorName,
            'original' => $original,
            'mutated' => $mutated,
            'diff' => $diff,
        ];
    }

    private static function sourcePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $offset = strrpos($path, '/src/');
        $path = $offset === false ? ltrim($path, '/') : substr($path, $offset + 1);
        if (!str_starts_with($path, 'src/')) {
            throw new \RuntimeException(sprintf('Mutation source is outside src: %s.', $path));
        }

        return $path;
    }

    /** @param array<array-key, mixed> $stats */
    private static function stat(array $stats, string $name): int
    {
        $value = $stats[$name] ?? null;
        if (!is_int($value) || $value < 0) {
            throw new \RuntimeException(sprintf('Mutation report has invalid stats.%s.', $name));
        }

        return $value;
    }
}
