<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;

abstract class LocaleListMethodFixturePipeline implements FixturePipeline
{
    protected function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
        private readonly string $methodName,
    ) {}

    final public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $name = basename($fixturePath);
        $failuresByAssertion = $this->failuresByAssertion($name);
        if ($failuresByAssertion === [] || count($identities) !== count($failuresByAssertion)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap(sprintf('The %s fixture shape is not supported.', $this->methodName)),
            );
        }

        $assertions = [];
        foreach ($identities as $index => $identity) {
            $assertions[] = [
                ...$identity,
                'status' => $failuresByAssertion[$index] ? 'failing' : 'passing',
                'adaptations' => $this->adaptations($name, $index),
            ];
        }
        $failures = count(array_filter($failuresByAssertion));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['direct'],
            $assertions,
            count($failuresByAssertion),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($name, $fixturePath))],
        );
    }

    /** @return list<bool> */
    abstract protected function operationFailuresByAssertion(string $name): array;

    abstract protected function renderOperationBody(string $name): ?string;

    /** @return list<bool> */
    private function failuresByAssertion(string $name): array
    {
        return match ($name) {
            'branding.js' => $this->brandingFailuresByAssertion(),
            'name.js' => [(new \ReflectionMethod(Locale::class, $this->methodName))->getName() !== $this->methodName],
            'prop-desc.js' => $this->propertyDescriptorFailuresByAssertion(),
            default => $this->operationFailuresByAssertion($name),
        };
    }

    /** @return list<bool> */
    private function brandingFailuresByAssertion(): array
    {
        $reflection = new \ReflectionClass(Locale::class);
        if (!$reflection->hasMethod($this->methodName)) {
            return array_fill(0, 10, true);
        }

        $uninitialized = $reflection->newInstanceWithoutConstructor();
        /** @var list<mixed> $receivers */
        $receivers = [null, null, true, '', 'Symbol()', 1, new \stdClass(), Locale::class, $uninitialized];
        $failures = [false];
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

    /** @return list<bool> */
    private function propertyDescriptorFailuresByAssertion(): array
    {
        $reflection = new \ReflectionClass(Locale::class);
        if (!$reflection->hasMethod($this->methodName)) {
            return [true, true];
        }
        $method = $reflection->getMethod($this->methodName);

        return [false, !$method->isPublic() || $method->isStatic()];
    }

    private function invokeWithReceiver(mixed $receiver): void
    {
        if (!$receiver instanceof Locale) {
            throw new TypeError('Locale receiver is not initialized.');
        }

        (new \ReflectionMethod(Locale::class, $this->methodName))->invoke($receiver);
    }

    /** @return list<string> */
    private function adaptations(string $name, int $index): array
    {
        return match ($name) {
            'branding.js' => $index === 0
                ? ['ECMAScript function branding is represented by an existing PHP instance method.']
                : [
                    'Each JavaScript receiver case is preserved in source order; a PHP receiver adapter represents call-with-receiver semantics.',
                    'ECMAScript undefined and Symbol use the nearest PHP representations because PHP has no corresponding values.',
                ],
            'name.js' => [
                'The ECMAScript name value is represented by ReflectionMethod::getName().',
                'ECMAScript name-property descriptor flags have no PHP method-metadata counterpart.',
            ],
            'prop-desc.js' => $index === 0
                ? ['ECMAScript typeof function is represented by an existing PHP instance method.']
                : [
                    'The ECMAScript prototype data property is represented by a public, non-static PHP method.',
                    'ECMAScript writable, enumerable, and configurable flags have no PHP method-metadata counterparts.',
                ],
            default => $this->operationAdaptations($name, $index),
        };
    }

    /** @return list<string> */
    protected function operationAdaptations(string $_name, int $_index): array
    {
        return ['The ECMAScript Array is represented by a PHP list array.'];
    }

    private function render(string $name, string $fixturePath): string
    {
        $body = match ($name) {
            'branding.js' => str_replace('{{METHOD}}', $this->methodName, <<<'PHP'
                Assert::assertTrue(method_exists(Locale::class, '{{METHOD}}'));
                $uninitialized = (new ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
                $receivers = [null, null, true, '', 'Symbol()', 1, new stdClass(), Locale::class, $uninitialized];
                $invoke = static function (mixed $receiver): void {
                    if (!$receiver instanceof Locale) {
                        throw new TypeError('Locale receiver is not initialized.');
                    }
                    $receiver->{{METHOD}}();
                };
                foreach ($receivers as $receiver) {
                    $rejected = false;
                    try {
                        $invoke($receiver);
                    } catch (TypeError) {
                        $rejected = true;
                    }
                    Assert::assertTrue($rejected);
                }
                PHP),
            'name.js' => sprintf(
                "Assert::assertSame('%1\$s', (new ReflectionMethod(Locale::class, '%1\$s'))->getName());",
                $this->methodName,
            ),
            'prop-desc.js' => str_replace('{{METHOD}}', $this->methodName, <<<'PHP'
                Assert::assertTrue(method_exists(Locale::class, '{{METHOD}}'));
                $method = new ReflectionMethod(Locale::class, '{{METHOD}}');
                Assert::assertTrue($method->isPublic() && !$method->isStatic());
                PHP),
            default => $this->renderOperationBody($name) ?? throw new \LogicException(sprintf(
                'Unsupported %s fixture.',
                $this->methodName,
            )),
        };

        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Exception\TypeError;
            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            {$body}
            PHP . "\n";
    }
}
