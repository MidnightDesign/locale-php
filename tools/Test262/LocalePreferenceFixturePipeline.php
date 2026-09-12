<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\LocalePreferenceAssertion;

final readonly class LocalePreferenceFixturePipeline implements FixturePipeline
{
    public function __construct(
        private AssertionIdentityExtractor $assertionIdentities,
        private string $test262Revision,
        private string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $method = str_contains($fixturePath, '/getCalendars/') ? 'getCalendars' : 'getHourCycles';
        $fixture = basename($fixturePath);
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $results = LocalePreferenceAssertion::evaluate($method, $fixture);
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
        $partial = in_array($fixture, ['name.js', 'prop-desc.js'], true);

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures > 0 ? 'failing' : ($partial ? 'partially_translated' : 'passing'),
            ['direct'],
            $assertions,
            count($results),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($method, $fixture, $fixturePath))],
            $partial ? 'JavaScript property descriptor flags have no faithful ordinary PHP equivalent.' : null,
        );
    }

    /** @return list<string> */
    private function adaptations(string $fixture, int $index): array
    {
        return match ($fixture) {
            'branding.js' => $index === 0
                ? ['ECMAScript function branding is represented by an existing PHP instance method.']
                : ['Each JavaScript receiver case is preserved in source order through a PHP receiver adapter.'],
            'name.js' => [
                'The ECMAScript name value is represented by ReflectionMethod::getName(); descriptor flags are inapplicable.',
            ],
            'prop-desc.js' => $index === 0
                ? ['ECMAScript typeof function is represented by an existing PHP instance method.']
                : [
                    'The public PHP method represents method availability; JavaScript descriptor flags are inapplicable.',
                ],
            default => [
                'The ECMAScript Array is represented by a PHP list array and the upstream data search is preserved.',
            ],
        };
    }

    private function render(string $method, string $fixture, string $fixturePath): string
    {
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\LocalePreferenceAssertion;

            LocalePreferenceAssertion::assertFixture('{$method}', '{$fixture}');
            PHP . "\n";
    }
}
