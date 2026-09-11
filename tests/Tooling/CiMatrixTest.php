<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\Matrix;
use Midnight\Intl\Tools\Ci\PackageSmoke;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

#[CoversClass(Matrix::class)]
final class CiMatrixTest extends TestCase
{
    public function testGroupedJobsPreserveEveryRuntimeAndPackageBoundary(): void
    {
        $matrix = Matrix::fromFile(dirname(__DIR__, 2) . '/.ci/matrix.json');
        $jobs = $matrix->runtimeJobs();
        self::assertCount(28, $jobs);
        $expanded = [];
        $packages = $matrix->installLanes(false);
        foreach ($jobs as $job) {
            $lane = $job;
            unset($lane['runNative'], $lane['testPackage']);
            $expanded[] = $lane;
            if ($job['runNative']) {
                self::assertSame('Darwin', $job['osFamily']);
                self::assertSame('disabled', $job['extensionMode']);
                $expanded[] = [...$lane, 'extensionMode' => 'native'];
            }
            if ($job['testPackage']) {
                $packages[] = ['runner' => $job['runner'], 'php' => $job['php']];
            }
        }
        self::assertEqualsCanonicalizing($matrix->runtimeLanes(), $expanded);
        self::assertEqualsCanonicalizing($matrix->installLanes(), $packages);
        self::assertCount(4, $matrix->installLanes(false));
    }

    public function testMatrixCommandWorksWithoutVendorDependencies(): void
    {
        $root = dirname(__DIR__, 2);
        $directory = PackageSmoke::temporaryDirectory('intl-locale-matrix-bootstrap');
        try {
            mkdir($directory . '/tools/Ci', 0700, true);
            mkdir($directory . '/.ci', 0700, true);
            foreach (['tools/ci-matrix.php', 'tools/Ci/Matrix.php', '.ci/matrix.json'] as $file) {
                copy($root . '/' . $file, $directory . '/' . $file);
            }
            $matrix = Matrix::fromFile($directory . '/.ci/matrix.json');
            $selections = [
                'runtime' => $matrix->runtimeLanes(),
                'runtime-jobs' => $matrix->runtimeJobs(),
                'install' => $matrix->installLanes(),
                'arm-runtime' => $matrix->armRuntimeLanes(),
                'windows-x86-runtime' => $matrix->windowsX86RuntimeLanes(),
                'windows-ts-runtime' => $matrix->windowsThreadSafeRuntimeLanes(),
                'icu-runtime' => $matrix->icuRuntimeLanes(),
                'advisory-runtime' => $matrix->advisoryRuntimeLanes(),
            ];
            foreach ($selections as $selection => $expected) {
                $process = new Process([PHP_BINARY, $directory . '/tools/ci-matrix.php', $selection]);
                $process->mustRun();
                self::assertSame(
                    ['include' => $expected],
                    json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR),
                );
            }
            $process = new Process([PHP_BINARY, $directory . '/tools/ci-matrix.php', 'install', '--without-macos']);
            $process->mustRun();
            self::assertSame(
                ['include' => $matrix->installLanes(false)],
                json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR),
            );
        } finally {
            PackageSmoke::removeDirectory($directory);
        }
    }

    public function testItBuildsTheCompleteStablePhpOsAndExtensionMatrix(): void
    {
        $matrix = Matrix::fromFile(dirname(__DIR__, 2) . '/.ci/matrix.json');
        $lanes = $matrix->runtimeLanes();

        self::assertCount(32, $lanes);
        $runtimes = [
            'ubuntu-24.04' => ['Linux', 'x64'],
            'windows-2022' => ['Windows', 'x64'],
            'macos-15' => ['Darwin', 'arm64'],
        ];
        foreach ($runtimes as $runner => [$osFamily, $architecture]) {
            foreach (['8.2', '8.3', '8.4', '8.5'] as $php) {
                $extensionModes = $runner === 'macos-15' ? ['disabled', 'native'] : ['absent', 'disabled', 'native'];
                foreach ($extensionModes as $mode) {
                    self::assertContains(
                        [
                            'runner' => $runner,
                            'php' => $php,
                            'threadSafe' => false,
                            'integerSize' => 8,
                            'osFamily' => $osFamily,
                            'architecture' => $architecture,
                            'extensionMode' => $mode,
                        ],
                        $lanes,
                    );
                }
            }
        }
        self::assertNotContains(
            [
                'runner' => 'macos-15',
                'php' => '8.5',
                'threadSafe' => false,
                'integerSize' => 8,
                'osFamily' => 'Darwin',
                'architecture' => 'arm64',
                'extensionMode' => 'absent',
            ],
            $lanes,
        );
    }

    public function testItKeepsEndpointAndSpecializedCoverageExplicit(): void
    {
        $matrix = Matrix::fromFile(dirname(__DIR__, 2) . '/.ci/matrix.json');

        self::assertCount(6, $matrix->installLanes());
        self::assertCount(3, $matrix->armRuntimeLanes());
        $windowsX86 = $matrix->windowsX86RuntimeLanes();
        self::assertCount(6, $windowsX86);
        self::assertSame('8.2.33', $windowsX86[0]['runtimeVersion']);
        self::assertSame(
            '8732dac6084bcad5e8fc363efcd9bdc50afbfc469bc858eeee92da05bd21d8cc',
            $windowsX86[0]['runtimeSha256'],
        );
        self::assertCount(6, $matrix->windowsThreadSafeRuntimeLanes());
        $icu = $matrix->icuRuntimeLanes();
        self::assertCount(8, $icu);
        self::assertSame(
            ['oldest', 'below-74', 'at-least-74', 'newest'],
            array_values(array_unique(array_column($icu, 'boundary'))),
        );
        $advisory = $matrix->advisoryRuntimeLanes();
        self::assertCount(3, $advisory);
        self::assertSame(['8.6'], array_values(array_unique(array_column($advisory, 'php'))));
    }

    public function testItRejectsAnUnknownRunnerExtensionModeExclusion(): void
    {
        $directory = PackageSmoke::temporaryDirectory('intl-locale-matrix');

        try {
            $data = json_decode(
                (string) file_get_contents(dirname(__DIR__, 2) . '/.ci/matrix.json'),
                true,
                flags: JSON_THROW_ON_ERROR,
            );
            self::assertIsArray($data);
            self::assertIsArray($data['stableRunners']);
            self::assertIsArray($data['stableRunners'][0]);
            unset($data['stableRunners'][0]['extensionModes']);
            $data['stableRunners'][0]['excludedExtensionModes'] = ['surprise'];
            $path = $directory . '/matrix.json';
            file_put_contents($path, json_encode($data, JSON_THROW_ON_ERROR));

            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('unknown extension mode exclusion');

            Matrix::fromFile($path);
        } finally {
            PackageSmoke::removeDirectory($directory);
        }
    }
}
