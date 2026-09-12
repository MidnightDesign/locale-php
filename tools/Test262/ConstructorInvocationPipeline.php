<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;

final class ConstructorInvocationPipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        if (count($assertions) !== 3) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['reflection'],
                $assertions,
                new TranslationGap('Expected constructor availability and two call-without-new assertions.'),
            );
        }
        $available = (new \ReflectionClass(Locale::class))->isInstantiable();
        $evidence = [[
            ...$assertions[0],
            'status' => $available ? 'passing' : 'failing',
            'adaptations' => ['PHP reflection verifies the equivalent constructible class contract.'],
            'executions' => [[
                'id' => 'constructible-class',
                'assertionId' => $assertions[0]['id'],
                'representation' => 'reflection',
                'status' => $available ? 'passing' : 'failing',
            ]],
        ]];
        foreach (array_slice($assertions, 1) as $assertion) {
            $evidence[] = [
                ...$assertion,
                'status' => 'inapplicable',
                'reason' => 'PHP constructors cannot be invoked as ordinary functions, so undefined NewTarget has no PHP call form.',
                'adaptations' => [
                    'PHP requires construction with new and rejects an ordinary class call syntactically.',
                ],
            ];
        }

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $available ? 'partially_translated' : 'failing',
            ['reflection'],
            $evidence,
            1,
            $available ? 0 : 1,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
            'Calling a constructor without new has no runtime invocation equivalent in PHP.',
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

            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            Assert::assertTrue((new ReflectionClass(Locale::class))->isInstantiable());
            PHP . "\n";
    }
}
