<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\MatrixMutationScore;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MatrixMutationScore::class)]
final class MatrixMutationScoreTest extends TestCase
{
    public function testItCountsEveryMutantAndExtensionModeAsAnObligation(): void
    {
        $evidence = MatrixMutationScore::aggregate([
            'absent' => $this->report(total: 4, killed: 4),
            'disabled' => $this->report(total: 4, killed: 3, killedByStaticAnalysis: 1),
            'native' => $this->report(total: 4, killed: 4),
        ]);

        self::assertSame(12, $evidence['obligations']);
        self::assertSame(12, $evidence['killed']);
        self::assertSame(100.0, $evidence['score']);
        self::assertTrue($evidence['passing']);
        self::assertSame(['absent', 'disabled', 'native'], array_keys($evidence['modes']));
    }

    public function testAnEscapeInOneModeCannotHideBehindAnotherMode(): void
    {
        $evidence = MatrixMutationScore::aggregate([
            'absent' => $this->report(total: 2, killed: 2),
            'disabled' => $this->report(total: 2, killed: 1, escaped: 1),
            'native' => $this->report(total: 2, killed: 2),
        ]);

        self::assertSame(83.3333, $evidence['score']);
        self::assertFalse($evidence['passing']);
        self::assertSame(1, $evidence['failures']['escaped']);
    }

    /** @return array{stats: array<string, int>} */
    private function report(
        int $total,
        int $killed,
        int $killedByStaticAnalysis = 0,
        int $escaped = 0,
    ): array {
        return ['stats' => [
            'totalMutantsCount' => $total,
            'killedCount' => $killed,
            'killedByStaticAnalysisCount' => $killedByStaticAnalysis,
            'escapedCount' => $escaped,
            'notCoveredCount' => 0,
            'errorCount' => 0,
            'syntaxErrorCount' => 0,
            'skippedCount' => 0,
            'ignoredCount' => 0,
            'timeOutCount' => 0,
        ]];
    }
}
