<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\MatrixMutationScore;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(MatrixMutationScore::class)]
final class MatrixMutationScoreTest extends TestCase
{
    public function testItRetainsEveryMutantAndExtensionModeObligation(): void
    {
        $evidence = MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/Spec/Locale.php'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);

        self::assertSame(2, $evidence['mutants']);
        self::assertSame(6, $evidence['obligations']);
        self::assertSame(6, $evidence['killed']);
        self::assertSame(100.0, $evidence['score']);
        self::assertTrue($evidence['passing']);
        self::assertSame(['porcelain', 'spec'], array_keys($evidence['campaigns']));
        self::assertSame(['absent', 'disabled', 'native'], array_keys($evidence['mutations'][0]['modes']));
        self::assertSame(['absent', 'disabled', 'native'], $evidence['mutations'][0]['applicableModes']);
        self::assertSame(['killed', 'killed', 'killed'], array_values($evidence['mutations'][0]['modes']));
    }

    #[DataProvider('failingResultProvider')]
    public function testAFailedObligationInOneModeCannotHideBehindKillsInOtherModes(
        string $result,
        string $failure,
    ): void {
        $campaign = $this->campaign('src/Spec/Locale.php');
        $campaign['disabled'] = $this->report('src/Spec/Locale.php', $result);

        $evidence = MatrixMutationScore::aggregate([
            'spec' => $campaign,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);

        self::assertSame(83.3333, $evidence['score']);
        self::assertFalse($evidence['passing']);
        self::assertSame(1, $evidence['failures'][$failure]);
    }

    /** @return iterable<string, array{string, string}> */
    public static function failingResultProvider(): iterable
    {
        yield 'escaped' => ['escaped', 'escaped'];
        yield 'uncovered' => ['uncovered', 'uncovered'];
        yield 'timed out' => ['timeouted', 'timedOut'];
        yield 'errored' => ['errored', 'errored'];
        yield 'syntax error' => ['syntaxErrors', 'syntaxErrors'];
        yield 'ignored' => ['ignored', 'ignored'];
        yield 'static analysis' => ['killedByStaticAnalysis', 'staticAnalysis'];
    }

    public function testItRejectsAnInconsistentMutantSetAcrossModes(): void
    {
        $campaign = $this->campaign('src/Spec/Locale.php');
        $campaign['native'] = $this->report('src/Spec/Locale.php', mutatedCode: 'return "different";');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('spec/native mutant set differs from spec/absent');

        MatrixMutationScore::aggregate([
            'spec' => $campaign,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
    }

    public function testItAcceptsOnlyTheDeclaredFailingCampaignAsATemporaryExpectedFailure(): void
    {
        $spec = $this->campaign('src/Spec/Locale.php');
        $spec['disabled'] = $this->report('src/Spec/Locale.php', 'escaped');
        $evidence = MatrixMutationScore::aggregate([
            'spec' => $spec,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);

        $baseline = $this->expectedFailureBaseline();
        self::assertTrue(MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline));
        $porcelainBaseline = $baseline;
        $porcelainBaseline['campaign'] = 'porcelain';
        self::assertFalse(MatrixMutationScore::acceptsExpectedFailure($evidence, $porcelainBaseline));

        $passing = MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/Spec/Locale.php'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
        self::assertFalse(MatrixMutationScore::acceptsExpectedFailure($passing, $baseline));

        $porcelain = $this->campaign('src/Locale.php');
        $porcelain['native'] = $this->report('src/Locale.php', 'uncovered');
        $multipleFailures = MatrixMutationScore::aggregate([
            'spec' => $spec,
            'porcelain' => $porcelain,
        ]);
        self::assertFalse(MatrixMutationScore::acceptsExpectedFailure($multipleFailures, $baseline));
    }

    public function testItRejectsAMissingProjectCampaign(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Mutation evidence must contain porcelain and spec campaigns.');

        MatrixMutationScore::aggregate(['spec' => $this->campaign('src/Spec/Locale.php')]);
    }

    public function testItRejectsStatsThatDoNotMatchTheReportedMutants(): void
    {
        $campaign = $this->campaign('src/Spec/Locale.php');
        self::assertIsArray($campaign['native']['stats']);
        $campaign['native']['stats']['totalMutantsCount'] = 2;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('spec/native stats.totalMutantsCount is 2 but 1 mutants were reported');

        MatrixMutationScore::aggregate([
            'spec' => $campaign,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
    }

    public function testItRejectsSourceFilesAssignedToTheWrongCampaign(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('spec campaign contains non-spec source src/Locale.php');

        MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/Locale.php'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
    }

    public function testItRejectsGeneratedSourceFromTheMutationTarget(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('src/Internal/Data/LocaleAliases.php is not a hand-written mutation target.');

        MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/Internal/Data/LocaleAliases.php'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
    }

    public function testItRejectsAProductionSourceWithoutCampaignOwnership(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Mutation source src/NewEntryPoint.php has no campaign ownership.');

        MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/NewEntryPoint.php'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
    }

    /** @return array<string, array<string, mixed>> */
    private function campaign(string $file): array
    {
        return [
            'absent' => $this->report($file),
            'disabled' => $this->report($file),
            'native' => $this->report($file),
        ];
    }

    /** @return array<string, mixed> */
    private function expectedFailureBaseline(): array
    {
        return [
            'campaign' => 'spec',
            'modes' => [
                'absent' => ['obligations' => 1, 'killed' => 1, 'failures' => 0],
                'disabled' => ['obligations' => 1, 'killed' => 0, 'failures' => 1],
                'native' => ['obligations' => 1, 'killed' => 1, 'failures' => 0],
            ],
            'failures' => [
                'escaped' => 1,
                'uncovered' => 0,
                'errored' => 0,
                'syntaxErrors' => 0,
                'skipped' => 0,
                'ignored' => 0,
                'timedOut' => 0,
                'staticAnalysis' => 0,
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function report(
        string $file,
        string $result = 'killed',
        string $mutatedCode = 'return false;',
    ): array {
        $resultToStat = [
            'killed' => 'killedCount',
            'killedByStaticAnalysis' => 'killedByStaticAnalysisCount',
            'uncovered' => 'notCoveredCount',
            'escaped' => 'escapedCount',
            'errored' => 'errorCount',
            'syntaxErrors' => 'syntaxErrorCount',
            'ignored' => 'ignoredCount',
            'timeouted' => 'timeOutCount',
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

        return [
            'stats' => $stats,
            'escaped' => [],
            'timeouted' => [],
            'killed' => [],
            'killedByStaticAnalysis' => [],
            'errored' => [],
            'syntaxErrors' => [],
            'uncovered' => [],
            'ignored' => [],
            $result => [[
                'mutator' => [
                    'mutatorName' => 'FalseValue',
                    'originalSourceCode' => 'return true;',
                    'mutatedSourceCode' => $mutatedCode,
                    'originalFilePath' => 'C:\\project\\'.$file,
                    'originalStartLine' => 12,
                ],
                'diff' => "- return true;\n+ {$mutatedCode}",
                'processOutput' => '',
            ]],
        ];
    }
}
