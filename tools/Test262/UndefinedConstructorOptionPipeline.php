<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use Midnight\Intl\Tools\PhpExporter;

final class UndefinedConstructorOptionPipeline implements FixturePipeline
{
    /** @param list<string> $representations */
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly array $representations,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
        private readonly string $optionName,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        $cases = $this->extractCases($source, $assertions);
        if ($cases === null) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                $this->representations,
                $assertions,
                new TranslationGap('Undefined option assertions are not in the supported form.'),
            );
        }

        $generatedCases = [];
        $executionResults = [];
        foreach ($cases as $index => $case) {
            foreach ($this->representations as $representation) {
                $executionId = sprintf('assertion-%d-%s', $index + 1, $representation);
                $generatedCases[$executionId] = [
                    $case['assertionId'],
                    $case['tag'],
                    $this->optionName,
                    $representation,
                    $case['expected'],
                ];
                $executionResults[] = [
                    'id' => $executionId,
                    'assertionId' => $case['assertionId'],
                    'representation' => $representation,
                    ...$this->evaluate($case['tag'], $representation, $case['expected']),
                ];
            }
        }

        $adaptations = [
            'JavaScript undefined is represented by the canonical internal undefined value.',
            'The JavaScript options object is exercised as both an associative array and a plain PHP object.',
        ];
        $evidenceAssertions = [];
        foreach ($assertions as $assertion) {
            $results = array_values(array_filter(
                $executionResults,
                static fn(array $result): bool => $result['assertionId'] === $assertion['id'],
            ));
            $passing =
                $results !== []
                && array_filter($results, static fn(array $result): bool => $result['status'] !== 'passing') === [];
            $evidenceAssertions[] = [
                ...$assertion,
                'status' => $passing ? 'passing' : 'failing',
                'adaptations' => $adaptations,
                'executions' => $results,
            ];
        }
        $failureCount = count(array_filter(
            $executionResults,
            static fn(array $result): bool => $result['status'] === 'failing',
        ));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failureCount === 0 ? 'passing' : 'failing',
            $this->representations,
            $evidenceAssertions,
            count($executionResults),
            $failureCount,
            [GeneratedScript::primary($fixturePath, $this->render($generatedCases, $fixturePath))],
        );
    }

    /**
     * @param list<array{id: string, line: int, column: int, call: string, sha256: string}> $assertions
     * @return list<array{assertionId: string, tag: string, expected: string}>|null
     */
    private function extractCases(string $source, array $assertions): ?array
    {
        preg_match_all(
            "~assert\\.sameValue\\(\\s*new Intl\\.Locale\\('(?<tag>[^']+)', \\{{$this->optionName}: undefined\\}\\)\\.toString\\(\\),\\s*'(?<expected>[^']+)'~s",
            $source,
            $matches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );
        preg_match_all(
            "~assert\\.throws\\(RangeError, \\(\\) => new Intl\\.Locale\\('(?<tag>[^']+)', \\{{$this->optionName}: undefined\\}\\)\\)~s",
            $source,
            $throwMatches,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );

        $rows = [];
        foreach ($matches as $match) {
            $rows[] = ['offset' => $match[0][1], 'tag' => $match['tag'][0], 'expected' => $match['expected'][0]];
        }
        foreach ($throwMatches as $match) {
            $rows[] = ['offset' => $match[0][1], 'tag' => $match['tag'][0], 'expected' => RangeError::class];
        }
        usort($rows, static fn(array $left, array $right): int => $left['offset'] <=> $right['offset']);
        if (count($rows) !== count($assertions)) {
            return null;
        }

        return array_map(
            static fn(array $row, int $index): array => [
                'assertionId' => $assertions[$index]['id'],
                'tag' => $row['tag'],
                'expected' => $row['expected'],
            ],
            $rows,
            array_keys($rows),
        );
    }

    /** @return array{status: string, actual?: string, failure?: string} */
    private function evaluate(string $tag, string $representation, string $expected): array
    {
        return (
            $expected === RangeError::class
                ? ConstructorOptionAssertion::evaluateRangeError(
                    $tag,
                    $this->optionName,
                    ['type' => 'undefined'],
                    $representation,
                )
                : ConstructorOptionAssertion::evaluate(
                    $tag,
                    $this->optionName,
                    ['type' => 'undefined'],
                    $representation,
                    $expected,
                )
        );
    }

    /** @param array<string, array{string, string, string, string, string}> $cases */
    private function render(array $cases, string $fixturePath): string
    {
        $caseExport = preg_replace('/[ \t]+$/m', '', PhpExporter::export($cases));
        if ($caseExport === null) {
            throw new \RuntimeException('Unable to format the generated undefined option cases.');
        }
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Exception\RangeError;
            use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
            use PHPUnit\Framework\Assert;

            foreach ({$caseExport} as [\$assertionId, \$tag, \$optionName, \$representation, \$expected]) {
                \$result = \$expected === RangeError::class
                    ? ConstructorOptionAssertion::evaluateRangeError(\$tag, \$optionName, ['type' => 'undefined'], \$representation)
                    : ConstructorOptionAssertion::evaluate(\$tag, \$optionName, ['type' => 'undefined'], \$representation, \$expected);

                Assert::assertSame('passing', \$result['status'], \$assertionId.': '.(\$result['failure'] ?? 'unknown failure'));
            }
            PHP . "\n";
    }
}
