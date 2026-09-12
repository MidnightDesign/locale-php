<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class CollationsFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $name = basename($fixturePath);
        $evaluation = CollationsFixtureAssertions::evaluate($name);
        if ($evaluation === null || count($identities) !== count($evaluation['failuresByAssertion'])) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap('The getCollations fixture shape is not supported.'),
            );
        }

        $assertions = [];
        foreach ($identities as $index => $identity) {
            $assertions[] = [
                ...$identity,
                'status' => $evaluation['failuresByAssertion'][$index] ? 'failing' : 'passing',
                'adaptations' => ['The ECMAScript Array is represented by a PHP list array.'],
            ];
        }
        $failures = count(array_filter($evaluation['failuresByAssertion']));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['direct'],
            $assertions,
            count($evaluation['executions']),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($name, $fixturePath))],
        );
    }

    private function render(string $name, string $fixturePath): string
    {
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tools\Test262\CollationsFixtureAssertions;
            use PHPUnit\Framework\Assert;

            \$evaluation = CollationsFixtureAssertions::evaluate('{$name}');
            Assert::assertNotNull(\$evaluation);
            foreach (\$evaluation['executions'] as \$execution) {
                Assert::assertFalse(\$execution['failed']);
            }
            PHP . "\n";
    }
}
