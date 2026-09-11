<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\OptionObservation;
use Midnight\Intl\Tools\PhpExporter;

final class OptionObservationPipeline implements FixturePipeline
{
    /** @param list<string> $options */
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
        private readonly string $mode,
        private readonly array $options = [],
    ) {
        if (!in_array($mode, ['order', 'throws'], true)) {
            throw new \InvalidArgumentException(sprintf('Unsupported option observation mode: "%s".', $mode));
        }
        if (($mode === 'order') !== ($options === [])) {
            throw new \InvalidArgumentException(sprintf(
                'Option observation mode "%s" requires %s options.',
                $mode,
                $mode === 'order' ? 'no' : 'one or more',
            ));
        }
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        if (count($assertions) !== 1) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['behavioral_object'],
                $assertions,
                new TranslationGap('Expected exactly one source assertion.'),
            );
        }
        $results = [];
        if ($this->mode === 'order') {
            $expected = [
                'tag toString',
                'get language',
                'toString language',
                'get script',
                'toString script',
                'get region',
                'toString region',
                'get variants',
                'toString variants',
                'get calendar',
                'toString calendar',
                'get collation',
                'toString collation',
                'get hourCycle',
                'toString hourCycle',
                'get caseFirst',
                'toString caseFirst',
                'get numeric',
                'get numberingSystem',
                'toString numberingSystem',
            ];
            $actual = OptionObservation::getterOrder();
            $results[] = [
                'id' => 'getter-order',
                'assertionId' => $assertions[0]['id'],
                'representation' => 'behavioral_object',
                'status' => $actual === $expected ? 'passing' : 'failing',
                'actual' => json_encode($actual, JSON_THROW_ON_ERROR),
            ];
        } elseif ($this->mode === 'throws') {
            foreach ($this->options as $option) {
                $passing = OptionObservation::propagates($option);
                $results[] = [
                    'id' => 'throw-' . $option,
                    'assertionId' => $assertions[0]['id'],
                    'representation' => 'behavioral_object',
                    'status' => $passing ? 'passing' : 'failing',
                ];
            }
        }
        $failureCount = count(array_filter(
            $results,
            static fn(array $result): bool => $result['status'] === 'failing',
        ));
        $evidence = [[
            ...$assertions[0],
            'status' => $failureCount === 0 ? 'passing' : 'failing',
            'adaptations' => [
                'JavaScript accessors are represented by the internal lazy OptionBag bridge.',
                'JavaScript object string conversion is represented by PHP Stringable.',
            ],
            'executions' => $results,
        ]];

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failureCount === 0 ? 'passing' : 'failing',
            ['behavioral_object'],
            $evidence,
            count($results),
            $failureCount,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
        );
    }

    private function render(string $fixturePath): string
    {
        $provider = preg_replace(
            '/[ \t]+$/m',
            '',
            PhpExporter::export(array_map(static fn(string $option): array => [$option], $this->options)),
        );
        if ($provider === null) {
            throw new \RuntimeException('Unable to export throwing getter options.');
        }
        $body = $this->mode === 'order' ? <<<'PHP'
                Assert::assertSame([
                    'tag toString', 'get language', 'toString language', 'get script', 'toString script',
                    'get region', 'toString region', 'get variants', 'toString variants',
                    'get calendar', 'toString calendar', 'get collation', 'toString collation',
                    'get hourCycle', 'toString hourCycle', 'get caseFirst', 'toString caseFirst',
                    'get numeric', 'get numberingSystem', 'toString numberingSystem',
                ], OptionObservation::getterOrder());
                PHP : sprintf(<<<'PHP'
                foreach (%s as [$option]) {
                    Assert::assertTrue(OptionObservation::propagates($option));
                }
                PHP, $provider);

        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\OptionObservation;
            use PHPUnit\Framework\Assert;

            {$body}
            PHP . "\n";
    }
}
