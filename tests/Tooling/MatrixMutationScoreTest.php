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

    public function testMutantIdentityIgnoresLineNumbersAndWholeFileSource(): void
    {
        $first = MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/Spec/Locale.php'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
        $shiftedSpec = $this->campaign(
            'src/Spec/Locale.php',
            line: 200,
            originalSourceCode: "<?php\n// unrelated insertion\nreturn true;",
            mutatedSourceCode: "<?php\n// unrelated insertion\nreturn false;",
            diff: "@@ @@\n  changed nearby context\n- return true;\n+ return false;",
        );
        $shifted = MatrixMutationScore::aggregate([
            'spec' => $shiftedSpec,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);

        self::assertSame($first['mutations'][1]['id'], $shifted['mutations'][1]['id']);
    }

    public function testDuplicateMutantOccurrencesStayAttachedToSourceOrderAcrossResultBuckets(): void
    {
        $spec = $this->campaign('src/Spec/Locale.php');
        $spec['absent'] = $this->reportWithDuplicateMutations('src/Spec/Locale.php', 'escaped', 'killed');
        $spec['disabled'] = $this->reportWithDuplicateMutations('src/Spec/Locale.php', 'killed', 'escaped');
        $spec['native'] = $this->reportWithDuplicateMutations('src/Spec/Locale.php', 'escaped', 'killed');

        $evidence = MatrixMutationScore::aggregate([
            'spec' => $spec,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);

        self::assertSame(
            [
                ['absent' => 'escaped', 'disabled' => 'killed', 'native' => 'escaped'],
                ['absent' => 'killed', 'disabled' => 'escaped', 'native' => 'killed'],
            ],
            array_column(array_slice($evidence['mutations'], 1), 'modes'),
        );
    }

    public function testItAcceptsOnlyTheDeclaredFailingCampaignAsATemporaryExpectedFailure(): void
    {
        $spec = $this->campaign('src/Spec/Locale.php');
        $spec['disabled'] = $this->report('src/Spec/Locale.php', 'escaped');
        $evidence = MatrixMutationScore::aggregate([
            'spec' => $spec,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);

        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');
        self::assertSame(4, $baseline['format']);
        self::assertSame([['disabled' => 'escaped']], array_values($baseline['mutations']));
        self::assertTrue(MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline));
        $porcelainBaseline = $baseline;
        $porcelainBaseline['campaign'] = 'porcelain';
        try {
            MatrixMutationScore::acceptsExpectedFailure($evidence, $porcelainBaseline);
            self::fail('A baseline cannot reassign mutant identities to another campaign.');
        } catch (\RuntimeException $error) {
            self::assertStringContainsString('contains an unknown mutant identity', $error->getMessage());
        }

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

        $newSpecFailure = $this->campaign('src/Spec/Locale.php');
        $newSpecFailure['absent'] = $this->report('src/Spec/Locale.php', 'escaped');
        $regressed = MatrixMutationScore::aggregate([
            'spec' => $newSpecFailure,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
        self::assertFalse(MatrixMutationScore::acceptsExpectedFailure($regressed, $baseline));
    }

    public function testItCollapsesAnIdenticalFailureAcrossEveryMode(): void
    {
        $spec = $this->campaign('src/Spec/Locale.php', 'escaped');
        $evidence = MatrixMutationScore::aggregate([
            'spec' => $spec,
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);

        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');

        self::assertSame(['escaped'], array_values($baseline['mutations']));
        self::assertTrue(MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline));
    }

    public function testItRejectsAnUnknownExpectedFailureBaselineFormat(): void
    {
        $evidence = $this->expectedFailureEvidence();
        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');
        $baseline['format'] = 999;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('format must be 4');

        MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
    }

    public function testItRejectsUnknownExpectedFailureBaselineFields(): void
    {
        $evidence = $this->expectedFailureEvidence();
        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');
        $baseline['surprise'] = true;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('contains unknown fields');

        MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
    }

    public function testItRejectsAnUnknownExpectedFailureCampaign(): void
    {
        $evidence = $this->expectedFailureEvidence();
        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');
        $baseline['campaign'] = 'surprise';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('names an unknown campaign');

        MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
    }

    public function testItRejectsAnUnknownModeInTheExpectedFailureBaseline(): void
    {
        $evidence = $this->expectedFailureEvidence();
        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');
        $identity = array_key_first($baseline['mutations']);
        self::assertIsString($identity);
        $baseline['mutations'][$identity] = ['surprise' => 'escaped'];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('contains an unknown extension mode');

        MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
    }

    public function testItRejectsAnUnknownResultInTheExpectedFailureBaseline(): void
    {
        $evidence = $this->expectedFailureEvidence();
        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');
        $identity = array_key_first($baseline['mutations']);
        self::assertIsString($identity);
        $baseline['mutations'][$identity] = 'unexpected-result';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('contains an unknown result');

        MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
    }

    public function testItValidatesBaselineEntriesEvenWhenTheEvidencePasses(): void
    {
        $evidence = MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/Spec/Locale.php'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
        $baseline = [
            'format' => 4,
            'campaign' => 'spec',
            'mutations' => [str_repeat('a', 64) . ':1' => 'unexpected-result'],
        ];

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('contains an unknown result');

        MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
    }

    public function testItRejectsAStaleExpectedFailureMutantIdentity(): void
    {
        $evidence = $this->expectedFailureEvidence();
        $baseline = MatrixMutationScore::expectedFailureBaseline($evidence, 'spec');
        $baseline['mutations'][str_repeat('a', 64) . ':1'] = 'escaped';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('contains an unknown mutant identity');

        MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
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
    private function campaign(
        string $file,
        string $result = 'killed',
        int $line = 12,
        string $originalSourceCode = 'return true;',
        string $mutatedCode = 'return false;',
        ?string $mutatedSourceCode = null,
        ?string $diff = null,
    ): array {
        return [
            'absent' => $this->report(
                $file,
                $result,
                $mutatedCode,
                $line,
                $originalSourceCode,
                $mutatedSourceCode,
                $diff,
            ),
            'disabled' => $this->report(
                $file,
                $result,
                $mutatedCode,
                $line,
                $originalSourceCode,
                $mutatedSourceCode,
                $diff,
            ),
            'native' => $this->report(
                $file,
                $result,
                $mutatedCode,
                $line,
                $originalSourceCode,
                $mutatedSourceCode,
                $diff,
            ),
        ];
    }

    /**
     * @return array{
     *     passing: bool,
     *     campaigns: array<string, array{modes: array<string, array{obligations: int, killed: int, failures: int}>}>,
     *     mutations: list<array{id: string, campaign: string, source: string, line: int, mutator: string, original: string, mutated: string, diff: string, applicableModes: list<string>, modes: array<string, string>}>
     * }
     */
    private function expectedFailureEvidence(): array
    {
        return MatrixMutationScore::aggregate([
            'spec' => $this->campaign('src/Spec/Locale.php', 'escaped'),
            'porcelain' => $this->campaign('src/Locale.php'),
        ]);
    }

    /** @return array<string, mixed> */
    private function report(
        string $file,
        string $result = 'killed',
        string $mutatedCode = 'return false;',
        int $line = 12,
        string $originalSourceCode = 'return true;',
        ?string $mutatedSourceCode = null,
        ?string $diff = null,
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
                    'originalSourceCode' => $originalSourceCode,
                    'mutatedSourceCode' => $mutatedSourceCode ?? $mutatedCode,
                    'originalFilePath' => 'C:\\project\\' . $file,
                    'originalStartLine' => $line,
                ],
                'diff' => $diff ?? "- return true;\n+ {$mutatedCode}",
                'processOutput' => '',
            ]],
        ];
    }

    /** @return array<string, mixed> */
    private function reportWithDuplicateMutations(string $file, string $firstResult, string $secondResult): array
    {
        $report = $this->report($file, $firstResult);
        $rows = $report[$secondResult] ?? null;
        self::assertIsArray($rows);
        $rows[] = [
            'mutator' => [
                'mutatorName' => 'FalseValue',
                'originalSourceCode' => 'return true;',
                'mutatedSourceCode' => 'return false;',
                'originalFilePath' => 'C:\\project\\' . $file,
                'originalStartLine' => 24,
            ],
            'diff' => "- return true;\n+ return false;",
            'processOutput' => '',
        ];
        $report[$secondResult] = $rows;
        $stats = $report['stats'] ?? null;
        self::assertIsArray($stats);
        self::assertIsInt($stats['totalMutantsCount']);
        ++$stats['totalMutantsCount'];
        $resultToStat = [
            'killed' => 'killedCount',
            'escaped' => 'escapedCount',
        ];
        $stat = $resultToStat[$secondResult] ?? null;
        self::assertIsString($stat);
        self::assertIsInt($stats[$stat]);
        ++$stats[$stat];
        $report['stats'] = $stats;

        return $report;
    }
}
