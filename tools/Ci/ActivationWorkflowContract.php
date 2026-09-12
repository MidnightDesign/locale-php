<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

use Symfony\Component\Yaml\Yaml;

final class ActivationWorkflowContract
{
    private const INCIDENT_WORKFLOW = './.github/workflows/compatibility-incident.yml';
    private const QUALITY_WORKFLOW = './.github/workflows/ci-quality.yml';
    private const SCHEDULED_WORKFLOW = './.github/workflows/ci-scheduled.yml';

    /** @return list<string> */
    public static function validate(string $root): array
    {
        $failures = [];
        $paths = [
            '.github/workflows/pull-request.yml' => ['pull_request'],
            '.github/workflows/nightly.yml' => ['schedule', 'workflow_dispatch'],
            '.github/workflows/weekly.yml' => ['schedule', 'workflow_dispatch'],
            '.github/workflows/release.yml' => ['workflow_dispatch'],
        ];
        $workflows = [];
        foreach ($paths as $path => $expectedTriggers) {
            try {
                $workflow = Workflow::fromFile($root . '/' . $path);
            } catch (\RuntimeException $error) {
                $failures[] = $error->getMessage();

                continue;
            }
            $workflows[$path] = $workflow;
            if ($workflow->triggers() !== $expectedTriggers) {
                $failures[] = sprintf('%s has invalid activation triggers.', $path);
            }
        }

        self::validatePullRequest($workflows['.github/workflows/pull-request.yml'] ?? null, $failures);
        foreach ([
            [
                'path' => '.github/workflows/nightly.yml',
                'profile' => 'nightly',
                'evidence-job' => 'nightly',
                'required-result' => '${{ needs.nightly.result }}',
            ],
            [
                'path' => '.github/workflows/weekly.yml',
                'profile' => 'weekly',
                'evidence-job' => 'compatibility',
                'required-result' => '${{ needs.compatibility.result }}',
            ],
        ] as $scheduled) {
            self::validateScheduled($workflows[$scheduled['path']] ?? null, $scheduled, $failures);
        }
        self::validateRelease($workflows['.github/workflows/release.yml'] ?? null, $failures);
        self::validateDependabot($root, $failures);

        return $failures;
    }

    /** @param list<string> $failures */
    private static function validatePullRequest(?Workflow $workflow, array &$failures): void
    {
        $gate = $workflow?->jobs()['gate'] ?? null;
        if (
            $workflow === null
            || !is_array($gate)
            || ($gate['name'] ?? null) !== 'CI gate'
            || ($gate['if'] ?? null) !== '${{ always() }}'
            || ($gate['needs'] ?? null) !== ['runtime', 'quality']
            || ($gate['runs-on'] ?? null) !== 'ubuntu-24.04'
            || !$workflow->hasNamedStep('gate', 'Require successful evidence', [
                'env' => [
                    'RUNTIME_RESULT' => '${{ needs.runtime.result }}',
                    'QUALITY_RESULT' => '${{ needs.quality.result }}',
                ],
                'run' =>
                    "if [[ \"\$RUNTIME_RESULT\" != \"success\" || \"\$QUALITY_RESULT\" != \"success\" ]]; then\n"
                        . "  echo \"Runtime or quality evidence failed.\"\n"
                        . "  exit 1\n"
                        . "fi\n",
            ])
        ) {
            $failures[] = 'The pull-request workflow must expose the stable CI gate.';
        }
    }

    /**
     * @param array{path: string, profile: string, evidence-job: string, required-result: string} $expected
     * @param list<string> $failures
     */
    private static function validateScheduled(?Workflow $workflow, array $expected, array &$failures): void
    {
        $jobs = $workflow?->jobs() ?? [];
        $evidence = $jobs[$expected['evidence-job']] ?? null;
        $evidenceWith = is_array($evidence) && is_array($evidence['with'] ?? null) ? $evidence['with'] : [];
        $quality = $jobs['quality'] ?? null;
        $qualityWith = is_array($quality) && is_array($quality['with'] ?? null) ? $quality['with'] : [];
        if (
            !is_array($evidence)
            || ($evidence['uses'] ?? null) !== self::SCHEDULED_WORKFLOW
            || ($evidenceWith['profile'] ?? null) !== $expected['profile']
            || ($evidenceWith['timeout-minutes'] ?? null) !== 60
            || !is_array($quality)
            || ($quality['uses'] ?? null) !== self::QUALITY_WORKFLOW
            || ($qualityWith['timeout-minutes'] ?? null) !== 60
        ) {
            $failures[] = sprintf(
                'The %s template must run %s compatibility and quality evidence.',
                $expected['profile'],
                $expected['profile'],
            );
        }

        $incident = $jobs['incident'] ?? null;
        $incidentWith = is_array($incident) && is_array($incident['with'] ?? null) ? $incident['with'] : [];
        if (
            !$workflow?->hasPermission('issues', 'write')
            || !is_array($incident)
            || ($incident['if'] ?? null) !== '${{ always() }}'
            || ($incident['needs'] ?? null) !== [$expected['evidence-job'], 'quality']
            || ($incident['uses'] ?? null) !== self::INCIDENT_WORKFLOW
            || ($incidentWith['profile'] ?? null) !== $expected['profile']
            || ($incidentWith['required-result'] ?? null) !== $expected['required-result']
            || ($incidentWith['quality-result'] ?? null) !== '${{ needs.quality.result }}'
        ) {
            $failures[] = 'Scheduled workflows must be able to maintain compatibility incidents.';
        }

        if (
            $workflow?->concurrency() !== [
                'group' => 'compatibility-' . $expected['profile'],
                'cancel-in-progress' => false,
            ]
        ) {
            $failures[] = 'Scheduled compatibility workflows must serialize incident maintenance per profile.';
        }
    }

    /** @param list<string> $failures */
    private static function validateRelease(?Workflow $workflow, array &$failures): void
    {
        $jobs = $workflow?->jobs() ?? [];
        $incidentGate = $jobs['incident-gate'] ?? null;
        $release = $jobs['release'] ?? null;
        if (
            !$workflow?->hasPermission('issues', 'read')
            || !is_array($incidentGate)
            || ($incidentGate['name'] ?? null) !== 'Compatibility incident gate'
            || ($incidentGate['runs-on'] ?? null) !== 'ubuntu-24.04'
            || ($incidentGate['timeout-minutes'] ?? null) !== 5
            || !self::jobRunContains($incidentGate, 'label:compatibility-incident')
            || !is_array($release)
            || ($release['needs'] ?? null) !== 'incident-gate'
            || ($release['uses'] ?? null) !== './.github/workflows/ci-release.yml'
        ) {
            $failures[] = 'Release evidence must wait for the compatibility incident gate.';
        }
    }

    /** @param list<string> $failures */
    private static function validateDependabot(string $root, array &$failures): void
    {
        try {
            $dependabot = Yaml::parseFile($root . '/.github/dependabot.yml');
        } catch (\Throwable $error) {
            $failures[] = sprintf('Cannot parse .github/dependabot.yml: %s', $error->getMessage());
            $dependabot = null;
        }
        $updates = is_array($dependabot) ? $dependabot['updates'] ?? null : null;
        $ecosystems = [];
        if (is_array($updates)) {
            foreach ($updates as $update) {
                if (is_array($update) && is_string($update['package-ecosystem'] ?? null)) {
                    $ecosystems[] = $update['package-ecosystem'];
                }
            }
        }
        foreach (['composer', 'github-actions'] as $ecosystem) {
            if (!in_array($ecosystem, $ecosystems, true)) {
                $failures[] = sprintf('Dependabot must update %s.', $ecosystem);
            }
        }
    }

    /** @param array<string, mixed> $job */
    private static function jobRunContains(array $job, string $text): bool
    {
        $steps = is_array($job['steps'] ?? null) ? $job['steps'] : [];
        foreach ($steps as $step) {
            if (is_array($step) && is_string($step['run'] ?? null) && str_contains($step['run'], $text)) {
                return true;
            }
        }

        return false;
    }
}
