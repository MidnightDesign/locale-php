<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\ConstructorTagType;

final class InvalidTagFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $kind,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        $checks = ConstructorTagType::rejects($this->kind);
        if (count($assertions) !== (count($checks) + 1)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['native_value'],
                $assertions,
                new TranslationGap('Invalid-tag assertion count changed.'),
            );
        }
        $availability = ConstructorTagType::isConstructible();
        $evidence = [[
            ...$assertions[0],
            'status' => $availability ? 'passing' : 'failing',
            'adaptations' => ['PHP reflection verifies that Locale is a constructible class.'],
            'executions' => [[
                'id' => 'constructor-availability',
                'assertionId' => $assertions[0]['id'],
                'representation' => 'reflection',
                'status' => $availability ? 'passing' : 'failing',
            ]],
        ]];
        foreach ($checks as $index => $passing) {
            $assertion = $assertions[$index + 1];
            $evidence[] = [
                ...$assertion,
                'status' => $passing ? 'passing' : 'failing',
                'adaptations' => [
                    'The internal undefined and Symbol values preserve ECMAScript primitive identity where PHP has no native value.',
                ],
                'executions' => [[
                    'id' => 'invalid-' . $this->kind . '-' . ($index + 1),
                    'assertionId' => $assertion['id'],
                    'representation' => 'native_value',
                    'status' => $passing ? 'passing' : 'failing',
                ]],
            ];
        }
        $failures = (int) !$availability + count(array_filter($checks, static fn(bool $passing): bool => !$passing));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['native_value'],
            $evidence,
            count($assertions),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
        );
    }

    private function render(string $fixturePath): string
    {
        $kind = var_export($this->kind, true);
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\ConstructorTagType;
            use PHPUnit\Framework\Assert;

            Assert::assertTrue(ConstructorTagType::isConstructible());
            Assert::assertNotContains(false, ConstructorTagType::rejects({$kind}));
            PHP . "\n";
    }
}
