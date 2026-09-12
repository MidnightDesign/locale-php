<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\ReceiverBranding;

final class BrandingFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $member,
        private readonly BrandingFixtureMode $mode,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        $methodAvailable = $this->mode->isProperty() || ReceiverBranding::methodIsAvailable($this->member);
        $checks = $this->mode->isProperty()
            ? ReceiverBranding::property($this->member)
            : ReceiverBranding::method($this->member, $this->mode->includesConstructor());
        $expectedCount = $this->mode->isProperty()
            ? 1
            : ($this->mode->hasIndividualAssertions() ? count($checks) + 1 : 2);
        if (count($assertions) !== $expectedCount) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['native_receiver_binding', 'uninitialized_locale'],
                $assertions,
                new TranslationGap(sprintf('Expected %d branding assertion(s).', $expectedCount)),
            );
        }
        $failures =
            (int) !$methodAvailable + count(array_filter($checks, static fn(array $check): bool => !$check['passing']));
        $passing = $failures === 0;
        $evidence = [];
        if (!$this->mode->isProperty()) {
            $evidence[] = [
                ...$assertions[0],
                'status' => $methodAvailable ? 'passing' : 'failing',
                'adaptations' => ['PHP reflection verifies that the public instance method exists.'],
                'executions' => [[
                    'id' => 'public-method',
                    'assertionId' => $assertions[0]['id'],
                    'representation' => 'reflection',
                    'status' => $methodAvailable ? 'passing' : 'failing',
                ]],
            ];
        }
        if ($this->mode->hasIndividualAssertions()) {
            foreach ($checks as $index => $check) {
                $evidence[] = self::brandingEvidence($assertions[$index + 1], [$check]);
            }
        } else {
            $evidence[] = self::brandingEvidence($assertions[$expectedCount - 1], $checks);
        }

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $passing ? 'passing' : 'failing',
            $this->representations(),
            $evidence,
            count($checks) + ($this->mode->isProperty() ? 0 : 1),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
        );
    }

    /** @return list<string> */
    public function representations(): array
    {
        return (
            $this->mode->isProperty()
                ? ['native_receiver_binding', 'uninitialized_locale']
                : ['reflection', 'native_receiver_binding', 'uninitialized_locale']
        );
    }

    private function render(string $fixturePath): string
    {
        $call = $this->mode->isProperty()
            ? sprintf('ReceiverBranding::property(%s)', var_export($this->member, true))
            : sprintf(
                'ReceiverBranding::method(%s, %s)',
                var_export($this->member, true),
                var_export($this->mode->includesConstructor(), true),
            );
        $availability = $this->mode->isProperty()
            ? ''
            : sprintf("Assert::assertTrue(ReceiverBranding::methodIsAvailable(%s));\n", var_export(
                $this->member,
                true,
            ));
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\ReceiverBranding;
            use PHPUnit\Framework\Assert;

            {$availability}
            Assert::assertNotContains(false, array_column({$call}, 'passing'));
            PHP . "\n";
    }

    /**
     * @param array<string, mixed> $assertion
     * @param list<array{id: string, representation: string, passing: bool}> $checks
     * @return array<string, mixed>
     */
    private static function brandingEvidence(array $assertion, array $checks): array
    {
        $passing = !in_array(false, array_column($checks, 'passing'), true);

        return [
            ...$assertion,
            'status' => $passing ? 'passing' : 'failing',
            'adaptations' => [
                'PHP native method binding rejects arbitrary non-Locale receivers before the method body runs.',
                'A reflection-created uninitialized Locale represents the prototype or forged receiver and exercises the private brand check.',
            ],
            'executions' => array_map(static fn(array $check): array => [
                'id' => $check['id'],
                'assertionId' => $assertion['id'],
                'representation' => $check['representation'],
                'status' => $check['passing'] ? 'passing' : 'failing',
            ], $checks),
        ];
    }
}
