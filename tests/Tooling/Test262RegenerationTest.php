<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use PHPUnit\Framework\TestCase;

final class Test262RegenerationTest extends TestCase
{
    public function testCommittedTranslationsAreReproducible(): void
    {
        $command = sprintf(
            '%s %s --check 2>&1',
            escapeshellarg(PHP_BINARY),
            escapeshellarg(dirname(__DIR__, 2).'/tools/transpile-test262.php'),
        );
        exec($command, $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
    }

    public function testCommittedDataProjectionIsReproducible(): void
    {
        $command = sprintf(
            '%s %s --check 2>&1',
            escapeshellarg(PHP_BINARY),
            escapeshellarg(dirname(__DIR__, 2).'/tools/generate-data.php'),
        );
        exec($command, $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
    }
}
