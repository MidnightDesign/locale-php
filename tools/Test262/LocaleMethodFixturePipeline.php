<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;

final class LocaleMethodFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $method,
        private readonly string $kind,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {
        if (!in_array(
            $method,
            ['getCollations', 'getTextInfo', 'getTimeZones', 'getWeekInfo', 'maximize', 'minimize'],
            true,
        )) {
            throw new \InvalidArgumentException(sprintf('Unsupported Locale method "%s".', $method));
        }
        if (!in_array($kind, ['length', 'name', 'property'], true)) {
            throw new \InvalidArgumentException(sprintf('Unsupported Locale method fixture kind "%s".', $kind));
        }
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $reflection = new \ReflectionMethod(Locale::class, $this->method);
        $failure =
            !$reflection->isPublic()
            || $reflection->getName() !== $this->method
            || $reflection->getNumberOfRequiredParameters() !== 0;
        $status = $failure ? 'failing' : 'partially_translated';
        $assertions = array_map(fn(array $identity): array => [
            ...$identity,
            'status' => $this->assertionStatus($identity, $failure),
            'adaptations' => [$this->adaptation($identity)],
        ], $identities);

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $status,
            ['php_reflection'],
            $assertions,
            count($identities),
            $failure ? 1 : 0,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
            'JavaScript property descriptor flags have no faithful ordinary PHP equivalent; the method name, visibility, and arity assertions run.',
        );
    }

    /** @param array<string, mixed> $identity */
    private function assertionStatus(array $identity, bool $failure): string
    {
        if ($failure) {
            return 'failing';
        }

        return match ($this->kind) {
            'length', 'name' => 'partially_translated',
            'property' => ($identity['call'] ?? null) === 'verifyProperty' ? 'inapplicable' : 'passing',
            default => throw new \LogicException('Unsupported Locale method fixture kind.'),
        };
    }

    /** @param array<string, mixed> $identity */
    private function adaptation(array $identity): string
    {
        return match ($this->kind) {
            'length'
                => 'The JavaScript function length is represented by zero required PHP parameters; descriptor flags are inapplicable.',
            'name'
                => 'The JavaScript function name is represented by the PHP reflection method name; descriptor flags are inapplicable.',
            'property' => ($identity['call'] ?? null) === 'verifyProperty'
                ? 'JavaScript writability, enumerability, and configurability have no faithful ordinary PHP equivalent and are inapplicable.'
                : 'Public PHP method visibility represents the applicable method-availability assertion.',
            default => throw new \LogicException('Unsupported Locale method fixture kind.'),
        };
    }

    private function render(string $fixturePath): string
    {
        $method = $this->method;
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            \$method = new \ReflectionMethod(Locale::class, '{$method}');

            Assert::assertTrue(\$method->isPublic());
            Assert::assertSame('{$method}', \$method->getName());
            Assert::assertSame(0, \$method->getNumberOfRequiredParameters());
            PHP . "\n";
    }
}
