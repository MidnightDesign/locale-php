<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
use Midnight\Intl\Tools\PhpExporter;

/**
 * @phpstan-type Expectation array{assertion: int, property: string, expected: string|bool|null}
 * @phpstan-type Scenario array{tag: string, options?: array<string, mixed>, expectations: list<Expectation>}
 */
final class MappedLocaleStatePipeline implements FixturePipeline
{
    /** @param list<Scenario> $scenarios */
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
        private readonly array $scenarios,
    ) {}

    /** @return list<string> */
    public function phpRepresentations(): array
    {
        $representations = [];
        foreach ($this->scenarios as $scenario) {
            foreach (self::scenarioRepresentations($scenario) as $representation) {
                $representations[$representation] = true;
            }
        }

        return array_keys($representations);
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        $mapped = [];
        $generated = [];
        $executions = [];

        foreach ($this->scenarios as $scenarioIndex => $scenario) {
            $expectations = [];
            foreach ($scenario['expectations'] as $expectation) {
                $identity = $assertions[$expectation['assertion']] ?? null;
                if ($identity === null) {
                    return FixtureResult::translationGap(
                        $fixturePath,
                        $source,
                        $this->phpRepresentations(),
                        $assertions,
                        new TranslationGap('Mapped assertion index is absent.'),
                    );
                }
                $mapped[$expectation['assertion']] = true;
                $expectations[] = new LocaleStateExpectation(
                    $identity['id'],
                    $expectation['property'],
                    $expectation['expected'],
                );
            }

            $options = $scenario['options'] ?? null;
            foreach (self::scenarioRepresentations($scenario) as $representation) {
                $caseId = sprintf('scenario-%d-%s', $scenarioIndex + 1, $representation);
                $generated[$caseId] = [
                    $scenario['tag'],
                    $options,
                    $representation,
                    array_map(
                        static fn(LocaleStateExpectation $expectation): array => $expectation->toTuple(),
                        $expectations,
                    ),
                ];
                $results = LocaleStateAssertion::evaluate($scenario['tag'], $options, $representation, $expectations);

                foreach ($expectations as $expectationIndex => $expectation) {
                    $executions[] = [
                        'id' => sprintf('%s-expectation-%d', $caseId, $expectationIndex + 1),
                        'assertionId' => $expectation->assertionId,
                        'representation' => $representation,
                        ...$results[$expectationIndex],
                    ];
                }
            }
        }

        if (count($mapped) !== count($assertions)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                $this->phpRepresentations(),
                $assertions,
                new TranslationGap('Every source assertion must have at least one mapped execution.'),
            );
        }

        $evidenceAssertions = [];
        foreach ($assertions as $assertion) {
            $results = array_values(array_filter(
                $executions,
                static fn(array $execution): bool => $execution['assertionId'] === $assertion['id'],
            ));
            $passing = array_filter($results, static fn(array $execution): bool => $execution['status'] !== 'passing')
            === [];
            $evidenceAssertions[] = [
                ...$assertion,
                'status' => $passing ? 'passing' : 'failing',
                'adaptations' => [
                    'Each JavaScript getter assertion is observed through the corresponding PHP readable property.',
                    'JavaScript undefined getter results map to PHP null.',
                    'Options-object scenarios run with associative-array and plain-object representations.',
                ],
                'executions' => $results,
            ];
        }
        $failureCount = count(array_filter(
            $executions,
            static fn(array $execution): bool => $execution['status'] === 'failing',
        ));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failureCount === 0 ? 'passing' : 'failing',
            $this->phpRepresentations(),
            $evidenceAssertions,
            count($executions),
            $failureCount,
            [GeneratedScript::primary($fixturePath, $this->render($generated, $fixturePath))],
        );
    }

    /**
     * @param Scenario $scenario
     * @return list<string>
     */
    private static function scenarioRepresentations(array $scenario): array
    {
        return isset($scenario['options']) ? ['associative_array', 'plain_object'] : ['direct'];
    }

    /** @param array<string, array{string, ?array<string, mixed>, string, list<array{string, string, string|bool|null}>}> $cases */
    private function render(array $cases, string $fixturePath): string
    {
        $export = preg_replace('/[ \t]+$/m', '', PhpExporter::export($cases));
        if ($export === null) {
            throw new \RuntimeException('Unable to export mapped locale-state cases.');
        }
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
            use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
            use PHPUnit\Framework\Assert;

            foreach ({$export} as [\$tag, \$options, \$representation, \$expectationTuples]) {
                \$expectations = array_map(LocaleStateExpectation::fromTuple(...), \$expectationTuples);
                \$results = LocaleStateAssertion::evaluate(\$tag, \$options, \$representation, \$expectations);
                foreach (\$expectations as \$index => \$expectation) {
                    \$result = \$results[\$index];
                    Assert::assertSame(
                        'passing',
                        \$result['status'],
                        \$expectation->assertionId.': '.(\$result['failure'] ?? 'unknown failure'),
                    );
                }
            }
            PHP . "\n";
    }
}
