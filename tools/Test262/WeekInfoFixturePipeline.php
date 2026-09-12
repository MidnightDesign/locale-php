<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\WeekInfoAssertion;

final readonly class WeekInfoFixturePipeline implements FixturePipeline
{
    public function __construct(
        private AssertionIdentityExtractor $assertionIdentities,
        private string $test262Revision,
        private string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $fixture = basename($fixturePath);
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $evaluation = WeekInfoAssertion::evaluate($fixture);
        $results = $evaluation['results'];
        if (count($identities) !== count($results)) {
            throw new \RuntimeException(sprintf('Fixture assertion count changed for %s.', $fixturePath));
        }

        $assertions = [];
        foreach ($identities as $index => $identity) {
            $assertions[] = [
                ...$identity,
                ...$results[$index],
                'adaptations' => $this->adaptations($fixture, $index),
            ];
        }
        $failures = count(array_filter($results, static fn(array $result): bool => $result['status'] === 'failing'));
        $partial = $fixture === 'output-object-keys.js';

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures > 0 ? 'failing' : ($partial ? 'partially_translated' : 'passing'),
            ['direct'],
            $assertions,
            $evaluation['executionCount'],
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($fixture, $fixturePath))],
            $partial ? 'JavaScript property descriptor flags have no faithful ordinary PHP equivalent.' : null,
        );
    }

    /** @return list<string> */
    private function adaptations(string $fixture, int $index): array
    {
        if ($fixture === 'output-object.js') {
            return ['An ordinary ECMAScript Object is represented by an associative PHP array.'];
        }
        if ($fixture === 'output-object-keys.js' && in_array($index, [1, 3], true)) {
            return [
                'JavaScript data-property descriptor flags have no faithful associative-array equivalent and are inapplicable.',
            ];
        }

        return [
            'The upstream data search, loop, result shape, and ISO weekday assertions are preserved directly in PHP.',
        ];
    }

    private function render(string $fixture, string $fixturePath): string
    {
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\WeekInfoAssertion;

            WeekInfoAssertion::assertFixture('{$fixture}');
            PHP . "\n";
    }
}
