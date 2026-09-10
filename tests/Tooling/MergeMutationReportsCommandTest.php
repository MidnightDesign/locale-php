<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class MergeMutationReportsCommandTest extends TestCase
{
    public function testItRetainsMachineReadableFailureEvidenceForAMissingCampaignReport(): void
    {
        $directory = PackageSmoke::temporaryDirectory('intl-locale-mutation-merge');
        $output = $directory.'/merged.json';

        try {
            $process = new Process([
                PHP_BINARY,
                dirname(__DIR__, 2).'/tools/merge-mutation-reports.php',
                $output,
                $directory.'/missing-spec-absent.json',
                $directory.'/spec-disabled.json',
                $directory.'/spec-native.json',
                $directory.'/porcelain-absent.json',
                $directory.'/porcelain-disabled.json',
                $directory.'/porcelain-native.json',
            ]);
            $process->run();

            self::assertSame(1, $process->getExitCode());
            self::assertFileExists($output);
            $evidence = json_decode((string) file_get_contents($output), true, flags: JSON_THROW_ON_ERROR);
            self::assertIsArray($evidence);
            self::assertFalse($evidence['passing']);
            self::assertIsString($evidence['error']);
            self::assertStringContainsString('Unable to read spec/absent mutation report', $evidence['error']);
        } finally {
            PackageSmoke::removeDirectory($directory);
        }
    }
}
