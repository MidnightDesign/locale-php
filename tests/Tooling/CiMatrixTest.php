<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\Matrix;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Matrix::class)]
final class CiMatrixTest extends TestCase
{
    public function testItBuildsTheCompleteStablePhpOsAndExtensionMatrix(): void
    {
        $matrix = Matrix::fromFile(dirname(__DIR__, 2).'/.ci/matrix.json');
        $lanes = $matrix->runtimeLanes();

        self::assertCount(36, $lanes);
        $runtimes = [
            'ubuntu-24.04' => ['Linux', 'x64'],
            'windows-2022' => ['Windows', 'x64'],
            'macos-15' => ['Darwin', 'arm64'],
        ];
        foreach ($runtimes as $runner => [$osFamily, $architecture]) {
            foreach (['8.2', '8.3', '8.4', '8.5'] as $php) {
                foreach (['absent', 'disabled', 'native'] as $mode) {
                    self::assertContains([
                        'runner' => $runner,
                        'php' => $php,
                        'threadSafe' => false,
                        'integerSize' => 8,
                        'osFamily' => $osFamily,
                        'architecture' => $architecture,
                        'extensionMode' => $mode,
                    ], $lanes);
                }
            }
        }
    }

    public function testItKeepsEndpointAndSpecializedCoverageExplicit(): void
    {
        $matrix = Matrix::fromFile(dirname(__DIR__, 2).'/.ci/matrix.json');

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
}
