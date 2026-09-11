<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use Midnight\Intl\Tools\PhpExporter;

/**
 * @phpstan-type OptionValue array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int}
 * @phpstan-type MappedCase array{assertion: int, tag: string, value: OptionValue, expected: string}
 */
final class MappedConstructorOptionPipeline implements FixturePipeline
{
    /**
     * @param list<string> $representations
     * @param list<MappedCase> $cases
     */
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly array $representations,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
        private readonly string $optionName,
        private readonly array $cases,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        foreach ($this->cases as $case) {
            if (!isset($assertions[$case['assertion']])) {
                return FixtureResult::translationGap(
                    $fixturePath,
                    $source,
                    $this->representations,
                    $assertions,
                    new TranslationGap('Mapped assertion index is absent.'),
                );
            }
        }
        $mappedAssertions = array_fill_keys(array_column($this->cases, 'assertion'), true);
        if (count($mappedAssertions) !== count($assertions)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                $this->representations,
                $assertions,
                new TranslationGap('Every source assertion must have at least one mapped execution.'),
            );
        }

        $generated = [];
        $executions = [];
        foreach ($this->cases as $caseIndex => $case) {
            foreach ($this->representations as $representation) {
                $assertionId = $assertions[$case['assertion']]['id'];
                $executionId = sprintf('case-%d-%s', $caseIndex + 1, $representation);
                $generated[$executionId] = [
                    $assertionId,
                    $case['tag'],
                    $this->optionName,
                    $case['value'],
                    $representation,
                    $case['expected'],
                ];
                $executions[] = [
                    'id' => $executionId,
                    'assertionId' => $assertionId,
                    'representation' => $representation,
                    ...self::evaluate(
                        $case['tag'],
                        $this->optionName,
                        $case['value'],
                        $representation,
                        $case['expected'],
                    ),
                ];
            }
        }

        $evidenceAssertions = [];
        foreach ($assertions as $assertion) {
            $results = array_values(array_filter(
                $executions,
                static fn(array $result): bool => $result['assertionId'] === $assertion['id'],
            ));
            $passing =
                $results !== []
                && array_filter($results, static fn(array $result): bool => $result['status'] !== 'passing') === [];
            $evidenceAssertions[] = [
                ...$assertion,
                'status' => $passing ? 'passing' : 'failing',
                'adaptations' => [
                    'The source loop is expanded into fixture-local PHP checks without multiplying upstream assertion coverage.',
                    'The JavaScript options object is exercised as both an associative array and a plain PHP object.',
                    'JavaScript undefined uses the canonical internal undefined value; object string conversion uses PHP Stringable.',
                ],
                'executions' => $results,
            ];
        }
        $failureCount = count(array_filter(
            $executions,
            static fn(array $result): bool => $result['status'] === 'failing',
        ));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failureCount === 0 ? 'passing' : 'failing',
            $this->representations,
            $evidenceAssertions,
            count($executions),
            $failureCount,
            [GeneratedScript::primary($fixturePath, $this->render($generated, $fixturePath))],
        );
    }

    /** @param array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int} $value
     * @return array{status: string, actual?: string, failure?: string}
     */
    private static function evaluate(
        string $tag,
        string $optionName,
        array $value,
        string $representation,
        string $expected,
    ): array {
        return $expected === RangeError::class
            ? ConstructorOptionAssertion::evaluateRangeError($tag, $optionName, $value, $representation)
            : ConstructorOptionAssertion::evaluate($tag, $optionName, $value, $representation, $expected);
    }

    /** @param array<string, array{string, string, string, OptionValue, string, string}> $cases */
    private function render(array $cases, string $fixturePath): string
    {
        $export = preg_replace('/[ \t]+$/m', '', PhpExporter::export($cases));
        if ($export === null) {
            throw new \RuntimeException('Unable to export mapped constructor cases.');
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

            foreach ({$export} as [\$assertionId, \$tag, \$optionName, \$value, \$representation, \$expected]) {
                \$result = \$expected === RangeError::class
                    ? ConstructorOptionAssertion::evaluateRangeError(\$tag, \$optionName, \$value, \$representation)
                    : ConstructorOptionAssertion::evaluate(\$tag, \$optionName, \$value, \$representation, \$expected);
                Assert::assertSame('passing', \$result['status'], \$assertionId.': '.(\$result['failure'] ?? 'unknown failure'));
            }
            PHP . "\n";
    }
}
