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
        self::assertSame(['8.2', '8.3', '8.4', '8.5'], $matrix->stablePhp());
        self::assertSame(['absent', 'disabled', 'native'], $matrix->extensionModes());

        foreach (['ubuntu-24.04', 'windows-2022', 'macos-15'] as $runner) {
            [$osFamily, $architecture] = match ($runner) {
                'ubuntu-24.04' => ['Linux', 'x64'],
                'windows-2022' => ['Windows', 'x64'],
                'macos-15' => ['Darwin', 'arm64'],
            };
            foreach ($matrix->stablePhp() as $php) {
                foreach ($matrix->extensionModes() as $mode) {
                    self::assertContains([
                        'runner' => $runner,
                        'php' => $php,
                        'extensionMode' => $mode,
                        'threadSafe' => false,
                        'integerSize' => 8,
                        'osFamily' => $osFamily,
                        'architecture' => $architecture,
                    ], $lanes);
                }
            }
        }
    }

    public function testItKeepsEndpointAndSpecializedCoverageExplicit(): void
    {
        $matrix = Matrix::fromFile(dirname(__DIR__, 2).'/.ci/matrix.json');

        self::assertCount(6, $matrix->installLanes());
        self::assertSame([
            ['runner' => 'ubuntu-24.04-arm', 'php' => '8.5', 'architecture' => 'arm64'],
        ], $matrix->armLanes());
        self::assertSame([
            [
                'runner' => 'windows-2022',
                'php' => '8.2',
                'architecture' => 'x86',
                'threadSafe' => false,
                'runtimeVersion' => '8.2.33',
                'runtimeUrl' => 'https://downloads.php.net/~windows/releases/php-8.2.33-nts-Win32-vs16-x86.zip',
                'runtimeSha256' => '8732dac6084bcad5e8fc363efcd9bdc50afbfc469bc858eeee92da05bd21d8cc',
            ],
            [
                'runner' => 'windows-2022',
                'php' => '8.5',
                'architecture' => 'x86',
                'threadSafe' => false,
                'runtimeVersion' => '8.5.10',
                'runtimeUrl' => 'https://downloads.php.net/~windows/releases/php-8.5.10-nts-Win32-vs17-x86.zip',
                'runtimeSha256' => 'de135a1dec0cca14d47865c957cf6dc40286a7859704488e153042b500387776',
            ],
        ], $matrix->windowsX86Lanes());
        self::assertSame([
            ['runner' => 'windows-2022', 'php' => '8.2', 'architecture' => 'x64', 'threadSafe' => true],
            ['runner' => 'windows-2022', 'php' => '8.5', 'architecture' => 'x64', 'threadSafe' => true],
        ], $matrix->windowsThreadSafeLanes());
        self::assertSame(['8.6'], $matrix->advisoryPhp());
        self::assertSame(['oldest', 'below-74', 'at-least-74', 'newest'], array_column($matrix->icuLanes(), 'boundary'));
        self::assertCount(3, $matrix->armRuntimeLanes());
        self::assertCount(6, $matrix->windowsX86RuntimeLanes());
        self::assertCount(6, $matrix->windowsThreadSafeRuntimeLanes());
        self::assertCount(8, $matrix->icuRuntimeLanes());
        self::assertCount(3, $matrix->advisoryRuntimeLanes());
        self::assertCount(15, $matrix->specializedRuntimeLanes());
    }
}
