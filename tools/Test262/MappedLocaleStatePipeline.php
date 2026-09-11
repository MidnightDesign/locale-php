<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;
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

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        $mapped = [];
        $generated = [];
        $executions = [];
        $executionNumber = 0;

        foreach ($this->scenarios as $scenario) {
            $representations = isset($scenario['options']) ? ['associative_array', 'plain_object'] : ['direct'];
            foreach ($scenario['expectations'] as $expectation) {
                $identity = $assertions[$expectation['assertion']] ?? null;
                if ($identity === null) {
                    return FixtureResult::translationGap(
                        $fixturePath,
                        $source,
                        ['direct'],
                        $assertions,
                        new TranslationGap('Mapped assertion index is absent.'),
                    );
                }
                $mapped[$expectation['assertion']] = true;
                foreach ($representations as $representation) {
                    $executionId = sprintf('case-%d-%s', ++$executionNumber, $representation);
                    $options = $scenario['options'] ?? null;
                    $generated[$executionId] = [
                        $identity['id'],
                        $scenario['tag'],
                        $options,
                        $representation,
                        $expectation['property'],
                        $expectation['expected'],
                    ];
                    $executions[] = [
                        'id' => $executionId,
                        'assertionId' => $identity['id'],
                        'representation' => $representation,
                        ...self::evaluate(
                            $scenario['tag'],
                            $options,
                            $representation,
                            $expectation['property'],
                            $expectation['expected'],
                        ),
                    ];
                }
            }
        }

        if (count($mapped) !== count($assertions)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
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
            ['direct', 'associative_array', 'plain_object'],
            $evidenceAssertions,
            count($executions),
            $failureCount,
            [GeneratedScript::primary($fixturePath, $this->render($generated, $fixturePath))],
        );
    }

    /**
     * @param array<string, mixed>|null $options
     * @return array{status: string, actual?: mixed, failure?: string}
     */
    private static function evaluate(
        string $tag,
        ?array $options,
        string $representation,
        string $property,
        string|bool|null $expected,
    ): array {
        try {
            $locale = match ($representation) {
                'direct' => new Locale($tag),
                'associative_array' => new Locale($tag, $options),
                'plain_object' => new Locale($tag, (object) $options),
                default => throw new \InvalidArgumentException('Unsupported representation.'),
            };
            $actual = $property === 'toString' ? $locale->toString() : $locale->{$property};
        } catch (\Throwable $error) {
            return ['status' => 'failing', 'failure' => sprintf('%s: %s', $error::class, $error->getMessage())];
        }

        return (
            $actual === $expected
                ? ['status' => 'passing', 'actual' => $actual]
                : [
                    'status' => 'failing',
                    'actual' => $actual,
                    'failure' => sprintf(
                        'Expected %s but received %s.',
                        var_export($expected, true),
                        var_export($actual, true),
                    ),
                ]
        );
    }

    /** @param array<string, array{string, string, ?array<string, mixed>, string, string, string|bool|null}> $cases */
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

            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            foreach ({$export} as [\$assertionId, \$tag, \$options, \$representation, \$property, \$expected]) {
                \$locale = match (\$representation) {
                    'direct' => new Locale(\$tag),
                    'associative_array' => new Locale(\$tag, \$options),
                    'plain_object' => new Locale(\$tag, (object) \$options),
                    default => throw new \InvalidArgumentException('Unsupported representation.'),
                };
                \$actual = \$property === 'toString' ? \$locale->toString() : \$locale->{\$property};

                Assert::assertSame(\$expected, \$actual, \$assertionId);
            }
            PHP . "\n";
    }
}
