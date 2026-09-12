<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\TypeError;
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
        if (!in_array($method, ['getCollations', 'getTextInfo', 'getTimeZones', 'maximize', 'minimize'], true)) {
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
        $methodFailure =
            !$reflection->isPublic()
            || $reflection->getName() !== $this->method
            || $reflection->getNumberOfRequiredParameters() !== 0;
        $failuresByAssertion = array_fill(0, count($identities), $methodFailure);
        if ($this->usesExplicitBrandingReceivers()) {
            $failuresByAssertion = $this->brandingFailuresByAssertion($methodFailure);
        } elseif ($this->kind === 'branding') {
            $uninitialized = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
            try {
                $uninitialized->{$this->method}();
                $failuresByAssertion = array_fill(0, count($identities), true);
            } catch (TypeError) {
            }
        }

        $failureCount = count(array_filter($failuresByAssertion));
        $partiallyTranslated = in_array($this->kind, ['length', 'name', 'property'], true);
        $status = $failureCount > 0 ? 'failing' : ($partiallyTranslated ? 'partially_translated' : 'passing');
        $assertions = [];
        foreach ($identities as $index => $identity) {
            $assertions[] = [
                ...$identity,
                'status' => $this->assertionStatus($identity, $failuresByAssertion[$index]),
                'adaptations' => [$this->adaptation($identity, $index)],
            ];
        }

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $status,
            ['php_reflection'],
            $assertions,
            count($identities),
            $failureCount,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
            $partiallyTranslated
                ? 'JavaScript property descriptor flags have no faithful ordinary PHP equivalent; the method name, visibility, and arity assertions run.'
                : null,
        );
    }

    /** @return list<bool> */
    private function brandingFailuresByAssertion(bool $methodFailure): array
    {
        $uninitialized = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
        /** @var list<mixed> $receivers */
        $receivers = [null, null, true, '', 'Symbol()', 1, new \stdClass(), Locale::class, $uninitialized];
        $failures = [$methodFailure];
        foreach ($receivers as $receiver) {
            try {
                $this->invokeWithReceiver($receiver);
                $failures[] = true;
            } catch (TypeError) {
                $failures[] = false;
            }
        }

        return $failures;
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
    private function adaptation(array $identity, int $index): string
    {
        return match ($this->kind) {
            'branding' => $index === 0
                ? 'ECMAScript function branding is represented by an existing PHP instance method.'
                : 'Each JavaScript receiver case is preserved in source order; a PHP receiver adapter represents call-with-receiver semantics.',
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

    private function usesExplicitBrandingReceivers(): bool
    {
        return (
            $this->kind === 'branding'
            && in_array($this->method, ['getCollations', 'getTextInfo', 'getTimeZones'], true)
        );
    }

    private function invokeWithReceiver(mixed $receiver): void
    {
        if (!$receiver instanceof Locale) {
            throw new TypeError('Locale receiver is not initialized.');
        }

        $receiver->{$this->method}();
    }

    private function render(string $fixturePath): string
    {
        $method = $this->method;
        if ($this->usesExplicitBrandingReceivers()) {
            return $this->renderExplicitBranding($fixturePath);
        }
        $branding = $this->kind === 'branding' ? <<<'PHP'

                    $locale = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
                    try {
                        $locale->METHOD();
                        Assert::fail('Expected an uninitialized Locale to fail its brand check.');
                    } catch (TypeError) {
                        Assert::assertTrue(true);
                    }
                PHP : '';
        $branding = str_replace('METHOD', $method, $branding);

        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Exception\TypeError;
            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            \$method = new \ReflectionMethod(Locale::class, '{$method}');

            Assert::assertTrue(\$method->isPublic());
            Assert::assertSame('{$method}', \$method->getName());
            Assert::assertSame(0, \$method->getNumberOfRequiredParameters());
            {$branding}
            PHP . "\n";
    }

    private function renderExplicitBranding(string $fixturePath): string
    {
        $method = $this->method;

        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Exception\TypeError;
            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            Assert::assertTrue(method_exists(Locale::class, '{$method}'));
            \$uninitialized = (new ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
            \$receivers = [null, null, true, '', 'Symbol()', 1, new stdClass(), Locale::class, \$uninitialized];
            foreach (\$receivers as \$receiver) {
                \$rejected = false;
                try {
                    if (!\$receiver instanceof Locale) {
                        throw new TypeError('Locale receiver is not initialized.');
                    }
                    \$receiver->{$method}();
                } catch (TypeError) {
                    \$rejected = true;
                }
                Assert::assertTrue(\$rejected);
            }
            PHP . "\n";
    }
}
