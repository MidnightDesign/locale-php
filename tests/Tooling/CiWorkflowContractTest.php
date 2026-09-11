<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use Midnight\Intl\Tools\Ci\WorkflowContract;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WorkflowContract::class)]
final class CiWorkflowContractTest extends TestCase
{
    public function testPreparedWorkflowsPreserveTheCiPolicyWithoutActivatingIt(): void
    {
        self::assertSame([], WorkflowContract::validate(dirname(__DIR__, 2)));
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

    public function testItRejectsMutationSourceAreaOmissions(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/infection.spec.json5';
            $contents = (string) file_get_contents($path);
            $contents = str_replace(
                '["/^Locale\\\\.php$/", "/^Internal\\\\/Data\\\\/LocaleAliases\\\\.php$/"]',
                '["/^Locale\\\\.php$/", "Internal", "/^Internal\\\\/Data\\\\/LocaleAliases\\\\.php$/"]',
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

    public function testCommentsCannotStandInForActivationTriggers(): void
    {
        $root = $this->fixtureRoot();

        try {
            $path = $root . '/.github/ci/public-pull-request.yml';
            $contents = (string) file_get_contents($path);
            $contents = str_replace("on:\n  pull_request:", "on:\n  workflow_dispatch:\n\n#  pull_request:", $contents);
            file_put_contents($path, $contents);

            self::assertContains(
                '.github/ci/public-pull-request.yml has invalid activation triggers.',
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
            '.github/workflows/ci-runtime.yml',
            '.github/workflows/ci-runtime-lane.yml',
            '.github/workflows/ci-quality.yml',
            '.github/workflows/ci-scheduled.yml',
            '.github/workflows/ci-release.yml',
            '.github/ci/public-pull-request.yml',
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
