<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\ReceiverBranding;

final class BrandingFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $member,
        private readonly bool $property,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        $expectedCount = $this->property ? 1 : 2;
        if (count($assertions) !== $expectedCount) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['native_receiver_binding', 'uninitialized_locale'],
                $assertions,
                new TranslationGap(sprintf('Expected %d branding assertion(s).', $expectedCount)),
            );
        }
        $checks = $this->property ? ReceiverBranding::property($this->member) : ReceiverBranding::method($this->member);
        $passing = !in_array(false, $checks, true);
        $brandingAssertion = $assertions[$expectedCount - 1];
        $evidence = [];
        if (!$this->property) {
            $evidence[] = [
                ...$assertions[0],
                'status' => 'passing',
                'adaptations' => ['PHP reflection verifies that the public instance method exists.'],
                'executions' => [[
                    'id' => 'public-method',
                    'assertionId' => $assertions[0]['id'],
                    'representation' => 'reflection',
                    'status' => 'passing',
                ]],
            ];
        }
        $evidence[] = [
            ...$brandingAssertion,
            'status' => $passing ? 'passing' : 'failing',
            'adaptations' => [
                'PHP native method binding rejects arbitrary non-Locale receivers before the method body runs.',
                'A reflection-created uninitialized Locale represents the prototype or forged receiver and exercises the private brand check.',
            ],
            'executions' => array_map(
                static fn(bool $result, int $index): array => [
                    'id' => 'invalid-receiver-' . ($index + 1),
                    'assertionId' => $brandingAssertion['id'],
                    'representation' => $index === 7 ? 'uninitialized_locale' : 'native_receiver_binding',
                    'status' => $result ? 'passing' : 'failing',
                ],
                $checks,
                array_keys($checks),
            ),
        ];

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $passing ? 'passing' : 'failing',
            ['native_receiver_binding', 'uninitialized_locale'],
            $evidence,
            count($checks) + ($this->property ? 0 : 1),
            count(array_filter($checks, static fn(bool $result): bool => !$result)),
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
        );
    }

    private function render(string $fixturePath): string
    {
        $call = $this->property
            ? sprintf('ReceiverBranding::property(%s)', var_export($this->member, true))
            : sprintf('ReceiverBranding::method(%s)', var_export($this->member, true));
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\ReceiverBranding;
            use PHPUnit\Framework\Assert;

            Assert::assertNotContains(false, {$call});
            PHP . "\n";
    }
}
