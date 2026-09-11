<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;
use Midnight\Intl\Tests\Test262\Harness\ReceiverBranding;

final class LocaleMethodFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $method,
        private readonly string $kind,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {
        if (!in_array($method, ['getTextInfo', 'maximize', 'minimize'], true)) {
            throw new \InvalidArgumentException(sprintf('Unsupported Locale method "%s".', $method));
        }
        if (!in_array($kind, ['branding', 'length', 'name', 'property'], true)) {
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
        $brandingChecks = [];
        if ($this->kind === 'branding') {
            $brandingChecks = ReceiverBranding::method($this->method);
            $failure = $failure || in_array(false, $brandingChecks, true);
        }

        $partiallyTranslated = in_array($this->kind, ['length', 'name', 'property'], true);
        $status = $failure ? 'failing' : ($partiallyTranslated ? 'partially_translated' : 'passing');
        $assertions = array_map(function (array $identity) use ($failure, $brandingChecks): array {
            $evidence = [
                ...$identity,
                'status' => $this->assertionStatus($identity, $failure),
                'adaptations' => [$this->adaptation($identity)],
            ];
            if ($this->kind === 'branding' && $identity['call'] === 'assert.throws') {
                $evidence['executions'] = array_map(
                    static fn(bool $passing, int $index): array => [
                        'id' => 'invalid-receiver-' . ($index + 1),
                        'assertionId' => $identity['id'],
                        'representation' => $index === 7 ? 'uninitialized_locale' : 'native_receiver_binding',
                        'status' => $passing ? 'passing' : 'failing',
                    ],
                    $brandingChecks,
                    array_keys($brandingChecks),
                );
            }

            return $evidence;
        }, $identities);
        $representations = $this->kind === 'branding'
            ? ['php_reflection', 'native_receiver_binding', 'uninitialized_locale']
            : ['php_reflection'];
        $executionCount = $this->kind === 'branding' ? count($brandingChecks) + 1 : count($identities);

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $status,
            $representations,
            $assertions,
            $executionCount,
            $failure ? 1 : 0,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
            $partiallyTranslated
                ? 'JavaScript property descriptor flags have no faithful ordinary PHP equivalent; the method name, visibility, and arity assertions run.'
                : null,
        );
    }

    /** @param array<string, mixed> $identity */
    private function assertionStatus(array $identity, bool $failure): string
    {
        if ($failure) {
            return 'failing';
        }

        return match ($this->kind) {
            'branding' => 'passing',
            'length', 'name' => 'partially_translated',
            'property' => ($identity['call'] ?? null) === 'verifyProperty' ? 'inapplicable' : 'passing',
            default => throw new \LogicException('Unsupported Locale method fixture kind.'),
        };
    }

    /** @param array<string, mixed> $identity */
    private function adaptation(array $identity): string
    {
        return match ($this->kind) {
            'branding'
                => 'PHP native binding rejects arbitrary receivers; an uninitialized Locale exercises the private brand check.',
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
        $branding = $this->kind === 'branding' ? <<<'PHP'

                    Assert::assertNotContains(false, ReceiverBranding::method('METHOD'));
                PHP : '';
        $branding = str_replace('METHOD', $method, $branding);
        $fixtureImport = $this->kind === 'branding'
            ? 'use Midnight\Intl\Tests\Test262\Harness\ReceiverBranding;'
            : 'use Midnight\Intl\Exception\TypeError;';

        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Spec\Locale;
            {$fixtureImport}
            use PHPUnit\Framework\Assert;

            \$method = new \ReflectionMethod(Locale::class, '{$method}');

            Assert::assertTrue(\$method->isPublic());
            Assert::assertSame('{$method}', \$method->getName());
            Assert::assertSame(0, \$method->getNumberOfRequiredParameters());
            {$branding}
            PHP . "\n";
    }
}
