<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\TagStringConversion;

final class TagStringConversionPipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        if (count($assertions) !== 2) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['behavioral_object'],
                $assertions,
                new TranslationGap('Expected the hint assertion and abrupt-completion assertion.'),
            );
        }
        $result = TagStringConversion::evaluate();
        $hintPassing = $result['hint'];
        $exceptionsPassing = !in_array(false, $result['exceptions'], true);
        $evidence = [
            [
                ...$assertions[0],
                'status' => $hintPassing ? 'passing' : 'failing',
                'adaptations' => ['The internal object bridge exposes the ECMAScript string-hint conversion hook.'],
                'executions' => [[
                    'id' => 'string-hint',
                    'assertionId' => $assertions[0]['id'],
                    'representation' => 'behavioral_object',
                    'status' => $hintPassing ? 'passing' : 'failing',
                ]],
            ],
            [
                ...$assertions[1],
                'status' => $exceptionsPassing ? 'passing' : 'failing',
                'adaptations' => [
                    'Symbol.toPrimitive, toString, and valueOf are represented by the internal lazy object bridge.',
                    'The source loop expands to eight fixture-local executions without multiplying assertion coverage.',
                ],
                'executions' => array_map(
                    static fn(bool $passing, int $index): array => [
                        'id' => 'abrupt-completion-' . ($index + 1),
                        'assertionId' => $assertions[1]['id'],
                        'representation' => 'behavioral_object',
                        'status' => $passing ? 'passing' : 'failing',
                    ],
                    $result['exceptions'],
                    array_keys($result['exceptions']),
                ),
            ],
        ];
        $failures = (int) !$hintPassing + count(array_filter($result['exceptions'], static fn(bool $v): bool => !$v));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['behavioral_object'],
            $evidence,
            9,
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
        );
    }

    private function render(string $fixturePath): string
    {
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\TagStringConversion;
            use PHPUnit\Framework\Assert;

            \$result = TagStringConversion::evaluate();
            Assert::assertTrue(\$result['hint']);
            Assert::assertNotContains(false, \$result['exceptions']);
            PHP . "\n";
    }
}
