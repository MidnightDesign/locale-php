<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

final class MergeMutationReportsCommandTest extends TestCase
{
    public function testExpectedFailureTurnsRedAsSoonAsTheSpecCampaignPasses(): void
    {
        $directory = PackageSmoke::temporaryDirectory('intl-locale-mutation-canary');
        $reports = $directory.'/reports';
        $output = $directory.'/merged.json';

        try {
            $this->writeCampaign($reports, 'spec', 'src/Spec/Locale.php', 'escaped');
            $this->writeCampaign($reports, 'porcelain', 'src/Locale.php', 'killed');
            $process = $this->runMerge($output, $reports, '--expect-failing=spec');

            self::assertSame(0, $process->getExitCode());
            $evidence = json_decode((string) file_get_contents($output), true, flags: JSON_THROW_ON_ERROR);
            self::assertIsArray($evidence);
            self::assertFalse($evidence['passing']);
            $expectedFailure = $evidence['expectedFailure'];
            self::assertIsArray($expectedFailure);
            self::assertTrue($expectedFailure['accepted']);

            $this->writeCampaign($reports, 'spec', 'src/Spec/Locale.php', 'killed');
            $process = $this->runMerge($output, $reports, '--expect-failing=spec');

            self::assertSame(1, $process->getExitCode());
            self::assertStringContainsString(
                'The spec mutation campaign now passes; remove its temporary expected-failure handling.',
                $process->getErrorOutput(),
            );
        } finally {
            PackageSmoke::removeDirectory($directory);
        }
    }

    public function testItRetainsMachineReadableFailureEvidenceForAMissingCampaignReport(): void
    {
        $directory = PackageSmoke::temporaryDirectory('intl-locale-mutation-merge');
        $output = $directory.'/merged.json';

        try {
            $process = new Process([
                PHP_BINARY,
                dirname(__DIR__, 2).'/tools/merge-mutation-reports.php',
                $output,
                $directory.'/reports',
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

    private function runMerge(string $output, string $reports, ?string $option = null): Process
    {
        $command = [
            PHP_BINARY,
            dirname(__DIR__, 2).'/tools/merge-mutation-reports.php',
            $output,
            $reports,
        ];
        if ($option !== null) {
            $command[] = $option;
        }
        $process = new Process($command);
        $process->run();

        return $process;
    }

    private function writeCampaign(string $directory, string $campaign, string $file, string $result): void
    {
        $target = $directory.'/'.$campaign;
        if (!is_dir($target)) {
            mkdir($target, 0700, true);
        }
        $resultToStat = [
            'killed' => 'killedCount',
            'escaped' => 'escapedCount',
        ];
        $stats = [
            'totalMutantsCount' => 1,
            'killedCount' => 0,
            'killedByStaticAnalysisCount' => 0,
            'escapedCount' => 0,
            'notCoveredCount' => 0,
            'errorCount' => 0,
            'syntaxErrorCount' => 0,
            'skippedCount' => 0,
            'ignoredCount' => 0,
            'timeOutCount' => 0,
        ];
        ++$stats[$resultToStat[$result]];
        $report = [
            'stats' => $stats,
            'escaped' => [],
            'timeouted' => [],
            'killed' => [],
            'killedByStaticAnalysis' => [],
            'errored' => [],
            'syntaxErrors' => [],
            'uncovered' => [],
            'ignored' => [],
        ];
        $report[$result][] = [
            'mutator' => [
                'mutatorName' => 'FalseValue',
                'originalSourceCode' => 'return true;',
                'mutatedSourceCode' => 'return false;',
                'originalFilePath' => 'C:\\project\\'.$file,
                'originalStartLine' => 12,
            ],
            'diff' => "- return true;\n+ return false;",
            'processOutput' => '',
        ];
        $encoded = json_encode($report, JSON_THROW_ON_ERROR);
        foreach (['absent', 'disabled', 'native'] as $mode) {
            file_put_contents($target.'/'.$mode.'.json', $encoded);
        }
    }
}
