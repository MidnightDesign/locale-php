<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use Midnight\Intl\Tools\Ci\WorkflowContract;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(WorkflowContract::class)]
final class CiWorkflowContractTest extends TestCase
{
    private const NATIVE_FOLLOW_UP_GUARD = "\${{ !cancelled() && inputs.run-native && steps.runtime_ready.outcome == 'success' }}";
    private const PACKAGE_FOLLOW_UP_GUARD = "\${{ !cancelled() && inputs.test-package && steps.runtime_ready.outcome == 'success' }}";

    public function testPullRequestCiIsActiveAndPreservesTheCiPolicy(): void
    {
        self::assertSame([], WorkflowContract::validate(dirname(__DIR__, 2)));
    }

    public function testRepositoryTextIsCheckedOutWithDeterministicLineEndings(): void
    {
        $contents = (string) file_get_contents(dirname(__DIR__, 2) . '/.gitattributes');

        self::assertStringContainsString('* text=auto eol=lf', $contents);
    }

    public function testItBindsEachActionNameToItsOwnPin(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-runtime-lane.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace(
                'actions/checkout@11d5960a326750d5838078e36cf38b85af677262',
                'actions/checkout@f3e473d116dcccaddc5834248c87452386958240',
                $contents,
            );
            file_put_contents($path, $contents);

            self::assertContains(
                'runtime-lane must use actions/checkout@11d5960a326750d5838078e36cf38b85af677262; found f3e473d116dcccaddc5834248c87452386958240.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsWeakenedScheduledCadences(): void
    {
        $root = $this->fixtureRoot();

        try {
            $nightly = $root . '/.github/ci/public-nightly.yml';
            $contents = (string) file_get_contents($nightly);
            $contents = preg_replace('/\n  quality:\n(?:    .*\n|      .*\n)*/', "\n", $contents);
            self::assertNotNull($contents);
            file_put_contents($nightly, $contents);

            $scheduled = $root . '/.github/workflows/ci-scheduled.yml';
            $contents = (string) file_get_contents($scheduled);
            $contents = str_replace(
                "  windows-x86:\n    if: \${{ inputs.profile == 'weekly' || inputs.profile == 'release' }}",
                "  windows-x86:\n    if: \${{ inputs.profile == 'release' }}",
                $contents,
            );
            file_put_contents($scheduled, $contents);

            $failures = WorkflowContract::validate($root);
            self::assertContains('Windows x86 must run for weekly and release profiles.', $failures);
            self::assertContains(
                'The nightly template must run nightly compatibility and quality evidence.',
                $failures,
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsASpecMutationCampaignThatUsesTheBroadCustomUnitSuite(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/infection.spec.json5';
            $contents = (string) file_get_contents($path);
            $contents = str_replace('--testsuite=test262-upstream', '--testsuite=unit', $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                'The spec mutation campaign must use the test262-upstream suite.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsAMissingReadOnlySpecRunWithoutIntl(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace(
                'chmod -R a-w . && vendor/bin/phpunit --do-not-cache-result --testsuite=test262-upstream',
                'vendor/bin/phpunit --testsuite=test262-upstream',
                $contents,
            );
            file_put_contents($path, $contents);

            self::assertContains(
                'The quality workflow must run the upstream spec suite without ext-intl or writable package storage.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsAMissingMutationCampaignLane(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace('campaign: [spec, porcelain]', 'campaign: [spec]', $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                'The mutation job must run the spec and porcelain campaigns.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsAMissingMutationExtensionModeLane(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace(
                'extensionMode: [absent, disabled, native]',
                'extensionMode: [absent, native]',
                $contents,
            );
            file_put_contents($path, $contents);

            self::assertContains(
                'The mutation job must run absent, disabled, and native extension modes.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsMutationArtifactsWithAnExtraDirectoryLevel(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace('path: build', 'path: build/ci', $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                'The mutation job must upload build as the artifact root.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRequiresTheTemporarySpecMutationFailureCanary(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace('--expect-failing=.ci/spec-mutation-expected-failure.json', '', $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                'The mutation score job must require the spec campaign to remain an expected failure.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsBroadMutationFailureSuppression(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace(
                "continue-on-error: \${{ matrix.campaign == 'spec' }}",
                'continue-on-error: true',
                $contents,
            );
            file_put_contents($path, $contents);

            self::assertContains(
                'The mutation job may continue on error only for the temporary spec failure.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsMutationSourceAreaOmissions(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/infection.spec.json5';
            $contents = (string) file_get_contents($path);
            $contents = str_replace(
                '["/^Locale\\\\.php$/", "/^Internal\\\\/Data\\\\//"]',
                '["/^Locale\\\\.php$/", "Internal", "/^Internal\\\\/Data\\\\//"]',
                $contents,
            );
            file_put_contents($path, $contents);

            self::assertContains(
                'The spec mutation campaign has an invalid production-source boundary.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testCommentsCannotStandInForRequiredCommands(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace('- run: composer data:check', '- run: true # composer data:check', $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                'quality workflow is missing a run command containing composer data:check.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testPhpstanRunsWithCliArgumentsRegistered(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/ci-quality.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace('register_argc_argv=On', 'register_argc_argv=Off', $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                'quality workflow is missing register_argc_argv=On.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRequiresMagoForFormatting(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/composer.json';
            $contents = (string) file_get_contents($path);
            $contents = str_replace('"style": "mago format --check"', '"style": "php-cs-fixer check"', $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                'The Composer style script must check formatting with Mago.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testCommentsCannotStandInForActivationTriggers(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/workflows/pull-request.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace("on:\n  pull_request:", "on:\n  workflow_dispatch:\n\n#  pull_request:", $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                '.github/workflows/pull-request.yml has invalid activation triggers.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testItRejectsDroppingNativeChecksFromGroupedRuntimeJobs(): void
    {
        $root = $this->fixtureRoot();
        try {
            $path = $root . '/.github/workflows/ci-runtime.yml';
            $contents = (string) file_get_contents($path);
            file_put_contents($path, str_replace(
                'run-native: ${{ matrix.runNative }}',
                'run-native: false',
                $contents,
            ));
            self::assertContains('runtime workflow is missing run-native.', WorkflowContract::validate($root));
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    #[DataProvider('followUpGuardMutations')]
    public function testItRejectsWeakeningAFollowUpGuard(
        string $stepName,
        string $originalGuard,
        string $weakenedGuard,
    ): void {
        $root = $this->fixtureRoot();
        try {
            $path = $root . '/.github/workflows/ci-runtime-lane.yml';
            $contents = (string) file_get_contents($path);
            file_put_contents($path, str_replace(
                "      - name: {$stepName}\n        if: {$originalGuard}",
                "      - name: {$stepName}\n        if: {$weakenedGuard}",
                $contents,
            ));
            self::assertContains(
                sprintf('runtime lane workflow has an invalid %s step.', $stepName),
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    /** @return iterable<string, array{string, string, string}> */
    public static function followUpGuardMutations(): iterable
    {
        yield 'native test' => ['Test native mode', self::NATIVE_FOLLOW_UP_GUARD, '${{ inputs.run-native }}'];
        yield 'native provenance' => [
            'Record native provenance',
            self::NATIVE_FOLLOW_UP_GUARD,
            'always()',
        ];
        yield 'native upload' => ['Upload native evidence', self::NATIVE_FOLLOW_UP_GUARD, 'always()'];
        yield 'package test' => [
            'Test package installation',
            self::PACKAGE_FOLLOW_UP_GUARD,
            '${{ inputs.test-package }}',
        ];
    }

    public function testTheNativeArtifactNameIsDerivedFromTheRuntimeIdentity(): void
    {
        $contents = (string) file_get_contents(dirname(__DIR__, 2) . '/.github/workflows/ci-runtime-lane.yml');

        self::assertStringNotContainsString('native-artifact-name:', $contents);
        self::assertStringContainsString(
            'name: runtime-${{ inputs.runner }}-php-${{ inputs.php }}-intl-native',
            $contents,
        );
    }

    public function testTheRuntimeLaneRejectsAnInvalidNativeFollowUpPairing(): void
    {
        $contents = (string) file_get_contents(dirname(__DIR__, 2) . '/.github/workflows/ci-runtime-lane.yml');

        self::assertStringContainsString(
            "(inputs.run-native || inputs.test-package) && (inputs.os-family != 'Darwin'",
            $contents,
        );
        self::assertStringContainsString('inputs.test-package && !inputs.run-native', $contents);
    }

    public function testItRejectsInstallingDependenciesBeforeMatrixGeneration(): void
    {
        $root = $this->fixtureRoot();
        try {
            $path = $root . '/.github/workflows/ci-runtime.yml';
            $contents = (string) file_get_contents($path);
            file_put_contents($path, str_replace(
                '      - id: matrix',
                "      - run: composer install\n      - id: matrix",
                $contents,
            ));
            self::assertContains(
                'Matrix generation must use runner PHP without provisioning or Composer installation.',
                WorkflowContract::validate($root),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    private function fixtureRoot(): string
    {
        $source = dirname(__DIR__, 2);
        $root = PackageSmoke::temporaryDirectory('intl-locale-workflow-contract');
        foreach ([
            '.ci/action-pins.json',
            '.ci/spec-mutation-expected-failure.json',
            '.github/workflows/ci-runtime.yml',
            '.github/workflows/ci-runtime-lane.yml',
            '.github/workflows/ci-quality.yml',
            '.github/workflows/ci-scheduled.yml',
            '.github/workflows/ci-release.yml',
            '.github/workflows/pull-request.yml',
            '.github/ci/public-nightly.yml',
            '.github/ci/public-weekly.yml',
            '.github/ci/public-release.yml',
            '.github/ci/public-dependabot.yaml.template',
            'composer.json',
            'Dockerfile',
            'infection.spec.json5',
            'infection.porcelain.json5',
            'phpunit.xml.dist',
        ] as $path) {
            $target = $root . '/' . $path;
            if (!is_dir(dirname($target))) {
                mkdir(dirname($target), 0700, true);
            }
            copy($source . '/' . $path, $target);
        }

        return $root;
    }
}
