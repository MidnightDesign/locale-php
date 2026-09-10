<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;

final class ConstructorFixturePipeline
{
    public function __construct(private readonly ConstructorOptionsScriptTranslator $translator)
    {
    }

    /**
     * @param list<string> $representations
     *
     * @return array{
     *     status: 'passing'|'failing',
     *     sourceAssertionCount: int,
     *     executionCount: int,
     *     executionFailures: int,
     *     phpRepresentations: list<string>,
     *     assertions: list<array<string, mixed>>,
     *     generatedCases: array<string, array{
     *         string,
     *         string,
     *         array{type: 'null'}|array{type: 'string'|'stringable', value: string},
     *         string,
     *         string
     *     }>
     * }|array{
     *     status: 'translation_gap',
     *     reason: string,
     *     executionFailures: 0,
     *     generatedCases: array{}
     * }
     */
    public function run(string $source, string $fixturePath, array $representations): array
    {
        try {
            $translation = $this->translator->translate($source, $fixturePath);
        } catch (\Throwable $error) {
            return [
                'status' => 'translation_gap',
                'reason' => sprintf('%s: %s', $error::class, $error->getMessage()),
                'executionFailures' => 0,
                'generatedCases' => [],
            ];
        }

        $generatedCases = [];
        $executionResults = [];
        foreach ($translation['cases'] as $case) {
            foreach ($representations as $representation) {
                $executionId = $case['id'].'-'.$representation;
                $generatedCases[$executionId] = [
                    $case['assertionId'],
                    $case['tag'],
                    $case['optionValue'],
                    $representation,
                    $case['expected'],
                ];
                $executionResults[] = [
                    'id' => $executionId,
                    'assertionId' => $case['assertionId'],
                    'representation' => $representation,
                    ...ConstructorOptionAssertion::evaluate(
                        $case['tag'],
                        'script',
                        $case['optionValue'],
                        $representation,
                        $case['expected'],
                    ),
                ];
            }
        }

        $adaptations = [
            'The JavaScript options object is exercised as both an associative array and a plain PHP object.',
            'The JavaScript toString method object is represented by an equivalent PHP Stringable object.',
            'The JavaScript loop is expanded into named PHPUnit data sets without multiplying upstream assertion coverage.',
        ];
        $assertions = [];
        foreach ($translation['assertions'] as $assertion) {
            $results = array_values(array_filter(
                $executionResults,
                static fn (array $result): bool => $result['assertionId'] === $assertion['id'],
            ));
            $passing = count(array_filter(
                $results,
                static fn (array $result): bool => $result['status'] === 'passing',
            )) === count($results);
            $assertions[] = [
                ...$assertion,
                'status' => $passing ? 'passing' : 'failing',
                'adaptations' => $adaptations,
                'executions' => $results,
            ];
        }
        $failureCount = count(array_filter(
            $executionResults,
            static fn (array $result): bool => $result['status'] === 'failing',
        ));

        return [
            'status' => $failureCount === 0 ? 'passing' : 'failing',
            'sourceAssertionCount' => count($translation['assertions']),
            'executionCount' => count($executionResults),
            'executionFailures' => $failureCount,
            'phpRepresentations' => $representations,
            'assertions' => $assertions,
            'generatedCases' => $generatedCases,
        ];
    }
}
