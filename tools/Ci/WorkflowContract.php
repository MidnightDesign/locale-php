<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

use Symfony\Component\Yaml\Yaml;

final class WorkflowContract
{
    /** @return list<string> */
    public static function validate(string $root): array
    {
        $failures = [];
        $paths = [
            'runtime' => '.github/workflows/ci-runtime.yml',
            'runtime-lane' => '.github/workflows/ci-runtime-lane.yml',
            'quality' => '.github/workflows/ci-quality.yml',
            'scheduled' => '.github/workflows/ci-scheduled.yml',
            'release' => '.github/workflows/ci-release.yml',
        ];
        $workflows = [];
        foreach ($paths as $name => $path) {
            try {
                $workflow = Workflow::fromFile($root . '/' . $path);
            } catch (\RuntimeException $error) {
                $failures[] = $error->getMessage();

                continue;
            }
            $workflows[$name] = $workflow;
            if ($workflow->triggers() !== ['workflow_call']) {
                $failures[] = sprintf('%s must be callable-only.', $path);
            }
            if ($workflow->hasScalarContaining('-latest')) {
                $failures[] = sprintf('%s uses a floating runner label.', $path);
            }
        }
        if (count($workflows) !== count($paths)) {
            return $failures;
        }

        self::validateActions($root, $workflows, $failures);
        self::validateMutationCampaigns($root, $failures);
        self::validateRuntime($workflows['runtime'], $workflows['runtime-lane'], $failures);
        self::validateQuality($workflows['quality'], $failures);
        self::validateScheduled($workflows['scheduled'], $failures);
        self::validateRelease($workflows['release'], $failures);
        self::validateAdvisoryPolicy($workflows, $failures);
        self::validateToolPins($root, $workflows, $failures);
        self::validateTimeouts($workflows, $failures);
        self::validateActivationTemplates($root, $failures);

        return $failures;
    }

    /** @param list<string> $failures */
    private static function validateRuntime(Workflow $runtime, Workflow $lane, array &$failures): void
    {
        self::requireRuns($runtime, ['php tools/ci-matrix.php runtime'], 'runtime workflow', $failures);
        self::requireUses($runtime, ['./.github/workflows/ci-runtime-lane.yml'], 'runtime workflow', $failures);
        self::requireScalars(
            $runtime,
            [
                "matrix.extensionMode == 'absent' && ':intl' || 'intl'",
                'matrix.integerSize',
                'matrix.osFamily',
                'matrix.architecture',
            ],
            'runtime workflow',
            $failures,
        );
        self::requireRuns(
            $lane,
            [
                'php tools/record-ci-provenance.php',
                'php tools/assert-ci-runtime.php',
            ],
            'runtime lane workflow',
            $failures,
        );
        self::requireScalars($lane, ["inputs.thread-safe && 'ts' || 'nts'"], 'runtime lane workflow', $failures);
        self::requireSettings($runtime, ['update' => true, 'thread-safe' => false], 'runtime workflow', $failures);
        self::requireSettings($lane, ['update' => true], 'runtime lane workflow', $failures);
    }

    /** @param list<string> $failures */
    private static function validateQuality(Workflow $workflow, array &$failures): void
    {
        self::requireRuns(
            $workflow,
            [
                'vendor/bin/phpstan',
                'vendor/bin/psalm',
                'vendor/bin/mago',
                'composer style',
                'composer data:check',
                'composer test262:check',
                'php tools/test-package-install.php',
                'php tools/assert-extension-version.php xdebug 3.5.3',
                'php tools/record-ci-provenance.php',
            ],
            'quality workflow',
            $failures,
        );
        self::requireScalars(
            $workflow,
            [
                'xdebug-3.5.3',
                'infection.${{ matrix.campaign }}.json5',
            ],
            'quality workflow',
            $failures,
        );

        $jobs = $workflow->jobs();
        $mutation = $jobs['mutation'] ?? null;
        $strategy = is_array($mutation) && is_array($mutation['strategy'] ?? null) ? $mutation['strategy'] : [];
        $matrix = is_array($strategy['matrix'] ?? null) ? $strategy['matrix'] : [];
        if (($matrix['campaign'] ?? null) !== MutationCampaigns::names()) {
            $failures[] = 'The mutation job must run the spec and porcelain campaigns.';
        }
        if (($matrix['extensionMode'] ?? null) !== MutationCampaigns::extensionModes()) {
            $failures[] = 'The mutation job must run absent, disabled, and native extension modes.';
        }
        self::requireJobRuns(
            $mutation,
            [
                'composer "mutation:${{ matrix.campaign }}"',
            ],
            'mutation job',
            $failures,
        );

        $score = $jobs['mutation-score'] ?? null;
        if (!is_array($score) || ($score['needs'] ?? null) !== 'mutation') {
            $failures[] = 'The mutation-score job must depend on the complete mutation matrix.';
        }
        self::requireJobRuns(
            $score,
            [
                'tools/merge-mutation-reports.php',
            ],
            'mutation-score job',
            $failures,
        );
    }

    /** @param list<string> $failures */
    private static function validateMutationCampaigns(string $root, array &$failures): void
    {
        foreach (MutationCampaigns::names() as $campaign) {
            $path = sprintf('%s/infection.%s.json5', $root, $campaign);
            $config = json_decode(self::read($path, $failures), true);
            if (!is_array($config)) {
                $failures[] = sprintf('infection.%s.json5 must contain a JSON object.', $campaign);

                continue;
            }
            $source = is_array($config['source'] ?? null) ? $config['source'] : [];
            if (
                ($source['directories'] ?? null) !== ['src']
                || ($source['excludes'] ?? null) !== MutationCampaigns::excludes($campaign)
            ) {
                $failures[] = sprintf('The %s mutation campaign has an invalid production-source boundary.', $campaign);
            }
            $suite = MutationCampaigns::suite($campaign);
            if (($config['testFrameworkOptions'] ?? null) !== '--testsuite=' . $suite) {
                $failures[] = sprintf('The %s mutation campaign must use the %s suite.', $campaign, $suite);
            }
            if (($config['minMsi'] ?? null) !== 100 || ($config['minCoveredMsi'] ?? null) !== 100) {
                $failures[] = sprintf('The %s mutation campaign must require 100%% MSI.', $campaign);
            }
            $mutators = is_array($config['mutators'] ?? null) ? $config['mutators'] : [];
            if (($mutators['@default'] ?? null) !== true || count($mutators) !== 1) {
                $failures[] = sprintf('The %s mutation campaign may not suppress mutants.', $campaign);
            }
        }

        $composer = json_decode(self::read($root . '/composer.json', $failures), true);
        $scripts = is_array($composer) && is_array($composer['scripts'] ?? null) ? $composer['scripts'] : [];
        foreach (MutationCampaigns::names() as $campaign) {
            $script = $scripts['mutation:' . $campaign] ?? null;
            if (
                !is_string($script)
                || !str_contains($script, '--with-uncovered')
                || str_contains($script, '--filter')
                || str_contains($script, '--git-diff')
            ) {
                $failures[] = sprintf(
                    'Composer mutation:%s must mutate uncovered code without source filters.',
                    $campaign,
                );
            }
        }

        self::requireText(
            self::read($root . '/phpunit.xml.dist', $failures),
            [
                '<testsuite name="test262-upstream">',
                '<file>tests/Test262/RunnerTest.php</file>',
                '<testsuite name="porcelain">',
                '<file>tests/LocaleTest.php</file>',
                '<exclude>tests/Test262/Generated</exclude>',
                '<exclude>tests/Test262/RunnerTest.php</exclude>',
                '<exclude>tests/LocaleTest.php</exclude>',
            ],
            'phpunit.xml.dist',
            $failures,
        );
    }

    /** @param list<string> $failures */
    private static function validateScheduled(Workflow $workflow, array &$failures): void
    {
        self::requireRuns(
            $workflow,
            [
                'arm-runtime',
                'windows-x86-runtime',
                'windows-ts-runtime',
                'icu-runtime',
                'advisory-runtime',
                'php tools/assert-ci-runtime.php',
                'matrix.runtimeUrl',
                'https://getcomposer.org/download/2.10.3/composer.phar',
                'Get-FileHash -Algorithm SHA256',
                'php tools/assert-ci-runtime.php 4 false Windows x86',
            ],
            'scheduled workflow',
            $failures,
        );
        self::requireUses($workflow, ['./.github/workflows/ci-runtime-lane.yml'], 'scheduled workflow', $failures);
        self::requireSettings($workflow, ['update' => true], 'scheduled workflow', $failures);

        $windowsX86 = $workflow->jobs()['windows-x86'] ?? null;
        $expectedCadence = "\${{ inputs.profile == 'weekly' || inputs.profile == 'release' }}";
        if (!is_array($windowsX86) || ($windowsX86['if'] ?? null) !== $expectedCadence) {
            $failures[] = 'Windows x86 must run for weekly and release profiles.';
        }
        foreach ($workflow->steps() as $step) {
            if (self::setupPhpHasInput($step, 'architecture')) {
                $failures[] = 'Scheduled lanes must not use the unsupported setup-php architecture input.';
            }
            if (self::setupPhpHasInput($step, 'ts')) {
                $failures[] = 'setup-php thread safety must use the phpts environment variable.';
            }
        }
    }

    /** @param list<string> $failures */
    private static function validateRelease(Workflow $workflow, array &$failures): void
    {
        self::requireUses(
            $workflow,
            [
                './.github/workflows/ci-runtime.yml',
                './.github/workflows/ci-quality.yml',
                './.github/workflows/ci-scheduled.yml',
            ],
            'release workflow',
            $failures,
        );
        self::requireRuns(
            $workflow,
            [
                'composer archive',
                'tools/test-packed-artifact.php',
            ],
            'release workflow',
            $failures,
        );
        self::requireSettings(
            $workflow,
            ['timeout-minutes' => 120, 'profile' => 'release'],
            'release workflow',
            $failures,
        );
    }

    /**
     * @param array<string, Workflow> $workflows
     * @param list<string> $failures
     */
    private static function validateActions(string $root, array $workflows, array &$failures): void
    {
        $pins = json_decode(self::read($root . '/.ci/action-pins.json', $failures), true);
        if (!is_array($pins)) {
            $failures[] = '.ci/action-pins.json must contain an object.';

            return;
        }

        $expectedPins = [];
        foreach ($pins as $action => $sha) {
            if (!is_string($sha) || preg_match('/^[a-f0-9]{40}$/D', $sha) !== 1) {
                $failures[] = '.ci/action-pins.json must map action names to full commit SHAs.';

                continue;
            }
            $expectedPins[$action] = $sha;
        }

        $usedPins = [];
        foreach ($workflows as $name => $workflow) {
            foreach ($workflow->uses() as $reference) {
                if (str_starts_with($reference, './')) {
                    continue;
                }
                if (preg_match('/^(?<action>[^@]+)@(?<sha>[^@]+)$/D', $reference, $match) !== 1) {
                    $failures[] = sprintf('%s uses an invalid action reference: %s.', $name, $reference);

                    continue;
                }
                $action = $match['action'];
                $sha = $match['sha'];
                if (!isset($expectedPins[$action])) {
                    $failures[] = sprintf('%s uses an action without an approved pin: %s.', $name, $action);
                } elseif ($expectedPins[$action] !== $sha) {
                    $failures[] = sprintf('%s must use %s@%s; found %s.', $name, $action, $expectedPins[$action], $sha);
                }
                $usedPins[$action] = true;
            }
        }
        foreach (array_keys($expectedPins) as $action) {
            if (!isset($usedPins[$action])) {
                $failures[] = sprintf('Approved action pin %s is not used by a reusable workflow.', $action);
            }
        }
    }

    /**
     * @param array<string, Workflow> $workflows
     * @param list<string> $failures
     */
    private static function validateAdvisoryPolicy(array $workflows, array &$failures): void
    {
        $lane = $workflows['runtime-lane']->jobs()['evidence'] ?? null;
        $scheduledJobs = $workflows['scheduled']->jobs();
        $advisoryJob = $scheduledJobs['advisory-php'] ?? null;
        $advisoryWith = is_array($advisoryJob) && is_array($advisoryJob['with'] ?? null) ? $advisoryJob['with'] : [];
        $advisory = $advisoryWith['advisory'] ?? null;
        $advisoryCount = 0;
        foreach ($scheduledJobs as $job) {
            $with = is_array($job['with'] ?? null) ? $job['with'] : [];
            if (($with['advisory'] ?? null) === true) {
                ++$advisoryCount;
            }
        }
        if (
            !is_array($lane)
            || ($lane['continue-on-error'] ?? null) !== '${{ inputs.advisory }}'
            || $advisory !== true
            || $advisoryCount !== 1
        ) {
            $failures[] = 'Only the unstable PHP lane may be advisory.';
        }
    }

    /**
     * @param array<string, Workflow> $workflows
     * @param list<string> $failures
     */
    private static function validateToolPins(string $root, array $workflows, array &$failures): void
    {
        $composer = json_decode(self::read($root . '/composer.json', $failures), true);
        $requirements = is_array($composer) ? $composer['require-dev'] ?? null : null;
        foreach ([
            'phpstan/phpstan',
            'vimeo/psalm',
            'carthage-software/mago',
            'infection/infection',
        ] as $tool) {
            $version = is_array($requirements) ? $requirements[$tool] ?? null : null;
            if (!is_string($version) || preg_match('/^\d+\.\d+\.\d+$/D', $version) !== 1) {
                $failures[] = sprintf('CI tool %s must use an exact version.', $tool);
            }
        }

        $scripts = is_array($composer) ? $composer['scripts'] ?? null : null;
        if (!is_array($scripts) || ($scripts['style'] ?? null) !== 'mago format --check') {
            $failures[] = 'The Composer style script must check formatting with Mago.';
        }
        if (!is_array($scripts) || ($scripts['format'] ?? null) !== 'mago format') {
            $failures[] = 'The Composer format script must format code with Mago.';
        }

        $setupPhpCount = 0;
        $composerPinCount = 0;
        foreach ($workflows as $workflow) {
            foreach ($workflow->steps() as $step) {
                if (is_string($step['uses'] ?? null) && str_starts_with($step['uses'], 'shivammathur/setup-php@')) {
                    ++$setupPhpCount;
                    $with = is_array($step['with'] ?? null) ? $step['with'] : [];
                    if (($with['tools'] ?? null) === 'composer:2.10.3') {
                        ++$composerPinCount;
                    }
                }
            }
        }
        if ($setupPhpCount !== $composerPinCount) {
            $failures[] = 'Every setup-php use must pin Composer 2.10.3.';
        }
        if (!$workflows['quality']->hasScalarContaining('xdebug-3.5.3')) {
            $failures[] = 'Mutation coverage must pin Xdebug 3.5.3.';
        }

        self::requireText(
            self::read($root . '/Dockerfile', $failures),
            ['composer:2.10.3', 'xdebug-3.5.3'],
            'Dockerfile',
            $failures,
        );
    }

    /**
     * @param array<string, Workflow> $workflows
     * @param list<string> $failures
     */
    private static function validateTimeouts(array $workflows, array &$failures): void
    {
        foreach (['runtime', 'runtime-lane', 'quality', 'scheduled'] as $name) {
            if (!$workflows[$name]->hasSetting('timeout-minutes', '${{ inputs.timeout-minutes }}')) {
                $failures[] = sprintf('%s workflow does not apply its timeout input.', $name);
            }
        }
    }

    /** @param list<string> $failures */
    private static function validateActivationTemplates(string $root, array &$failures): void
    {
        $templates = [
            '.github/ci/public-pull-request.yml' => ['pull_request'],
            '.github/ci/public-nightly.yml' => ['schedule', 'workflow_dispatch'],
            '.github/ci/public-weekly.yml' => ['schedule', 'workflow_dispatch'],
            '.github/ci/public-release.yml' => ['workflow_dispatch'],
        ];
        $workflows = [];
        foreach ($templates as $path => $expectedTriggers) {
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

        $nightly = $workflows['.github/ci/public-nightly.yml'] ?? null;
        $nightlyJobs = $nightly?->jobs() ?? [];
        $nightlyJob = $nightlyJobs['nightly'] ?? null;
        $nightlyWith = is_array($nightlyJob) && is_array($nightlyJob['with'] ?? null) ? $nightlyJob['with'] : [];
        $qualityJob = $nightlyJobs['quality'] ?? null;
        if (
            ($nightlyWith['profile'] ?? null) !== 'nightly'
            || !is_array($qualityJob)
            || ($qualityJob['uses'] ?? null) !== './.github/workflows/ci-quality.yml'
        ) {
            $failures[] = 'The nightly template must run nightly compatibility and quality evidence.';
        }

        $weekly = $workflows['.github/ci/public-weekly.yml'] ?? null;
        $weeklyJob = $weekly?->jobs()['compatibility'] ?? null;
        $weeklyWith = is_array($weeklyJob) && is_array($weeklyJob['with'] ?? null) ? $weeklyJob['with'] : [];
        if (($weeklyWith['profile'] ?? null) !== 'weekly') {
            $failures[] = 'The weekly template must run the weekly compatibility profile.';
        }

        try {
            $dependabot = Yaml::parseFile($root . '/.github/ci/public-dependabot.yaml.template');
        } catch (\Throwable $error) {
            $failures[] = sprintf('Cannot parse .github/ci/public-dependabot.yaml.template: %s', $error->getMessage());
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
                $failures[] = sprintf('The Dependabot template must update %s.', $ecosystem);
            }
        }
        if (is_file($root . '/.github/dependabot.yml')) {
            $failures[] = 'Dependabot must remain dormant until public activation.';
        }
    }

    /** @param array<string, mixed> $step */
    private static function setupPhpHasInput(array $step, string $input): bool
    {
        return (
            is_string($step['uses'] ?? null)
            && str_starts_with($step['uses'], 'shivammathur/setup-php@')
            && is_array($step['with'] ?? null)
            && array_key_exists($input, $step['with'])
        );
    }

    /**
     * @param list<string> $required
     * @param list<string> $failures
     */
    private static function requireScalars(Workflow $workflow, array $required, string $subject, array &$failures): void
    {
        foreach ($required as $text) {
            if (!$workflow->hasScalarContaining($text)) {
                $failures[] = sprintf('%s is missing %s.', $subject, $text);
            }
        }
    }

    /**
     * @param list<string> $required
     * @param list<string> $failures
     */
    private static function requireRuns(Workflow $workflow, array $required, string $subject, array &$failures): void
    {
        $commands = $workflow->runs();
        foreach ($required as $text) {
            $found = false;
            foreach ($commands as $command) {
                if (str_contains($command, $text)) {
                    $found = true;

                    break;
                }
            }
            if (!$found) {
                $failures[] = sprintf('%s is missing a run command containing %s.', $subject, $text);
            }
        }
    }

    /**
     * @param mixed $job
     * @param list<string> $required
     * @param list<string> $failures
     */
    private static function requireJobRuns(mixed $job, array $required, string $subject, array &$failures): void
    {
        $commands = [];
        $steps = is_array($job) && is_array($job['steps'] ?? null) ? $job['steps'] : [];
        foreach ($steps as $step) {
            if (is_array($step) && is_string($step['run'] ?? null)) {
                $commands[] = $step['run'];
            }
        }
        foreach ($required as $text) {
            $found = false;
            foreach ($commands as $command) {
                if (str_contains($command, $text)) {
                    $found = true;

                    break;
                }
            }
            if (!$found) {
                $failures[] = sprintf('%s is missing a run command containing %s.', $subject, $text);
            }
        }
    }

    /**
     * @param list<string> $required
     * @param list<string> $failures
     */
    private static function requireUses(Workflow $workflow, array $required, string $subject, array &$failures): void
    {
        $references = $workflow->uses();
        foreach ($required as $reference) {
            if (!in_array($reference, $references, true)) {
                $failures[] = sprintf('%s is missing workflow reference %s.', $subject, $reference);
            }
        }
    }

    /**
     * @param array<string, mixed> $required
     * @param list<string> $failures
     */
    private static function requireSettings(
        Workflow $workflow,
        array $required,
        string $subject,
        array &$failures,
    ): void {
        foreach ($required as $key => $value) {
            if (!$workflow->hasSetting($key, $value)) {
                $failures[] = sprintf('%s is missing %s.', $subject, $key);
            }
        }
    }

    /**
     * @param list<string> $required
     * @param list<string> $failures
     */
    private static function requireText(string $contents, array $required, string $subject, array &$failures): void
    {
        foreach ($required as $text) {
            if (!str_contains($contents, $text)) {
                $failures[] = sprintf('%s is missing %s.', $subject, $text);
            }
        }
    }

    /** @param list<string> $failures */
    private static function read(string $path, array &$failures): string
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            $failures[] = sprintf('Missing CI file %s.', $path);

            return '';
        }

        return $contents;
    }
}
