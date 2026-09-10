<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class WorkflowContract
{
    /** @return list<string> */
    public static function validate(string $root): array
    {
        $failures = [];
        $workflows = [
            'runtime' => '.github/workflows/ci-runtime.yml',
            'quality' => '.github/workflows/ci-quality.yml',
            'scheduled' => '.github/workflows/ci-scheduled.yml',
            'release' => '.github/workflows/ci-release.yml',
        ];
        $contents = [];
        foreach ($workflows as $name => $path) {
            $contents[$name] = self::read($root.'/'.$path, $failures);
            if (!str_contains($contents[$name], "  workflow_call:\n")) {
                $failures[] = sprintf('%s must be callable-only.', $path);
            }
            if (preg_match('/^  (?:pull_request|push|schedule|workflow_dispatch):/m', $contents[$name]) === 1) {
                $failures[] = sprintf('%s activates hosted execution.', $path);
            }
            if (str_contains($contents[$name], '-latest')) {
                $failures[] = sprintf('%s uses a floating runner label.', $path);
            }
        }

        self::validateActions($root, $contents, $failures);
        self::requireText($contents['runtime'], [
            'php tools/ci-matrix.php runtime',
            "matrix.extensionMode == 'absent' && ':intl' || 'intl'",
            'php tools/record-ci-provenance.php',
            'php tools/assert-ci-runtime.php',
        ], 'runtime workflow', $failures);
        self::requireText($contents['quality'], [
            'vendor/bin/phpstan',
            'vendor/bin/psalm',
            'vendor/bin/mago',
            'vendor/bin/php-cs-fixer',
            'composer data:check',
            'composer test262:check',
            'composer mutation',
            'tools/merge-mutation-reports.php',
            'php tools/test-package-install.php',
        ], 'quality workflow', $failures);
        self::requireText($contents['scheduled'], [
            'arm-runtime',
            'windows-x86-runtime',
            'windows-ts-runtime',
            'icu-runtime',
            'advisory-runtime',
            'php tools/assert-ci-runtime.php',
        ], 'scheduled workflow', $failures);
        self::requireText($contents['release'], [
            './.github/workflows/ci-runtime.yml',
            './.github/workflows/ci-quality.yml',
            './.github/workflows/ci-scheduled.yml',
            'composer archive',
            'tools/test-packed-artifact.php',
        ], 'release workflow', $failures);

        $allWorkflows = implode("\n", $contents);
        if (substr_count($allWorkflows, 'continue-on-error: true') !== 1
            || !str_contains($contents['scheduled'], 'continue-on-error: true')) {
            $failures[] = 'Only the unstable PHP lane may be advisory.';
        }

        self::validateToolPins($root, $failures);
        self::validateTimeouts($contents, $failures);
        self::validateActivationTemplates($root, $failures);

        return $failures;
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

    /**
     * @param array<string, string> $workflows
     * @param list<string> $failures
     */
    private static function validateActions(string $root, array $workflows, array &$failures): void
    {
        $pinsJson = self::read($root.'/.ci/action-pins.json', $failures);
        $pins = json_decode($pinsJson, true);
        if (!is_array($pins)) {
            $failures[] = '.ci/action-pins.json must contain an object.';

            return;
        }
        $allowed = [];
        foreach ($pins as $pin) {
            if (is_string($pin)) {
                $allowed[$pin] = true;
            }
        }

        foreach ($workflows as $path => $workflow) {
            preg_match_all('/uses:\s+([^\s@]+)@([a-f0-9]+)/', $workflow, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $sha = $match[2];
                if (strlen($sha) !== 40 || !isset($allowed[$sha])) {
                    $failures[] = sprintf('%s uses an action outside the full approved pins: %s.', $path, $match[0]);
                }
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
    private static function validateToolPins(string $root, array &$failures): void
    {
        $composerJson = self::read($root.'/composer.json', $failures);
        $composer = json_decode($composerJson, true);
        $requirements = is_array($composer) ? ($composer['require-dev'] ?? null) : null;
        foreach (['phpstan/phpstan', 'vimeo/psalm', 'carthage-software/mago', 'infection/infection', 'friendsofphp/php-cs-fixer'] as $tool) {
            $version = is_array($requirements) ? ($requirements[$tool] ?? null) : null;
            if (!is_string($version) || preg_match('/^\d+\.\d+\.\d+$/D', $version) !== 1) {
                $failures[] = sprintf('CI tool %s must use an exact version.', $tool);
            }
        }

        $workflowContents = implode("\n", [
            self::read($root.'/.github/workflows/ci-runtime.yml', $failures),
            self::read($root.'/.github/workflows/ci-quality.yml', $failures),
            self::read($root.'/.github/workflows/ci-scheduled.yml', $failures),
            self::read($root.'/.github/workflows/ci-release.yml', $failures),
        ]);
        if (substr_count($workflowContents, 'shivammathur/setup-php@') !== substr_count($workflowContents, 'tools: composer:2.10.3')) {
            $failures[] = 'Every setup-php use must pin Composer 2.10.3.';
        }
        if (!str_contains($workflowContents, 'xdebug-3.5.3')) {
            $failures[] = 'Mutation coverage must pin Xdebug 3.5.3.';
        }

        $dockerfile = self::read($root.'/Dockerfile', $failures);
        self::requireText($dockerfile, ['composer:2.10.3', 'xdebug-3.5.3'], 'Dockerfile', $failures);
    }

    /**
     * @param array<string, string> $contents
     * @param list<string> $failures
     */
    private static function validateTimeouts(array $contents, array &$failures): void
    {
        foreach (['runtime', 'quality', 'scheduled'] as $workflow) {
            if (!str_contains($contents[$workflow], 'timeout-minutes: ${{ inputs.timeout-minutes }}')) {
                $failures[] = sprintf('%s workflow does not apply its timeout input.', $workflow);
            }
        }
        self::requireText($contents['release'], ['timeout-minutes: 120', 'profile: release'], 'release workflow', $failures);
    }

    /** @param list<string> $failures */
    private static function validateActivationTemplates(string $root, array &$failures): void
    {
        $templates = [
            '.github/ci/public-pull-request.yml' => '  pull_request:',
            '.github/ci/public-nightly.yml' => '      profile: nightly',
            '.github/ci/public-weekly.yml' => '      profile: weekly',
            '.github/ci/public-release.yml' => '  workflow_dispatch:',
            '.github/ci/public-dependabot.yaml.template' => 'package-ecosystem: composer',
        ];
        foreach ($templates as $path => $trigger) {
            $contents = self::read($root.'/'.$path, $failures);
            if (!str_contains($contents, $trigger)) {
                $failures[] = sprintf('%s is missing its activation trigger.', $path);
            }
        }
        if (is_file($root.'/.github/dependabot.yml')) {
            $failures[] = 'Dependabot must remain dormant until public activation.';
        }
    }
}
