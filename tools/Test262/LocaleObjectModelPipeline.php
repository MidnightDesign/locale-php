<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\LocaleObjectModel;

final class LocaleObjectModelPipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $mode,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        [$checks, $method] = match ($this->mode) {
            'subclassing' => [LocaleObjectModel::subclassing(), 'subclassing'],
            'extensibility' => [[LocaleObjectModel::isExtensible()], 'isExtensible'],
            'instance' => [[LocaleObjectModel::hasBasePrototype()], 'hasBasePrototype'],
            default => throw new \LogicException('Unknown object-model fixture mode.'),
        };
        if (count($assertions) !== count($checks)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['php_object_model'],
                $assertions,
                new TranslationGap('Object-model assertion count changed.'),
            );
        }
        $evidence = [];
        foreach ($assertions as $index => $assertion) {
            $evidence[] = [
                ...$assertion,
                'status' => $checks[$index] ? 'passing' : 'failing',
                'adaptations' => [
                    'PHP inheritance, declared properties, and dynamic property storage preserve the fixture test intent.',
                ],
                'executions' => [[
                    'id' => $this->mode . '-' . ($index + 1),
                    'assertionId' => $assertion['id'],
                    'representation' => 'php_object_model',
                    'status' => $checks[$index] ? 'passing' : 'failing',
                ]],
            ];
        }
        $failures = count(array_filter($checks, static fn(bool $passing): bool => !$passing));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['php_object_model'],
            $evidence,
            count($checks),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath, $method))],
        );
    }

    private function render(string $fixturePath, string $method): string
    {
        $assertion = $this->mode === 'subclassing'
            ? "Assert::assertNotContains(false, LocaleObjectModel::{$method}());"
            : "Assert::assertTrue(LocaleObjectModel::{$method}());";
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Tests\Test262\Harness\LocaleObjectModel;
            use PHPUnit\Framework\Assert;

            {$assertion}
            PHP . "\n";
    }
}
