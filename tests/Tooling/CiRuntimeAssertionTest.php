<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class CiRuntimeAssertionTest extends TestCase
{
    public function testAnEmptyOptionalIcuExpectationIsIgnored(): void
    {
        $architecture = match (strtolower(php_uname('m'))) {
            'amd64', 'x86_64' => 'x64',
            'aarch64', 'arm64' => 'arm64',
            'i386', 'i686', 'x86' => 'x86',
            default => strtolower(php_uname('m')),
        };
        $process = new Process([
            PHP_BINARY,
            dirname(__DIR__, 2) . '/tools/assert-ci-runtime.php',
            (string) PHP_INT_SIZE,
            PHP_ZTS === 1 ? 'true' : 'false',
            PHP_OS_FAMILY,
            $architecture,
            PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
            '',
        ]);

        $process->mustRun();

        self::assertSame('', $process->getOutput());
    }
}
