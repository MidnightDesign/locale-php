<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;

final class TimeZonesFixturePipeline implements FixturePipeline
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
        $failuresByAssertion = $this->failuresByAssertion($name);
        if ($failuresByAssertion === [] || count($identities) !== count($failuresByAssertion)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap('The getTimeZones fixture shape is not supported.'),
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

    /** @return list<bool> Whether each source assertion failed, in source order. */
    private function failuresByAssertion(string $name): array
    {
        return match ($name) {
            'branding.js' => $this->brandingFailuresByAssertion(),
            'name.js' => [(new \ReflectionMethod(Locale::class, 'getTimeZones'))->getName() !== 'getTimeZones'],
            'output-array-sorted.js' => [!self::isSorted((new Locale('en-US'))->getTimeZones())],
            'output-array-undefined.js' => [(new Locale('en'))->getTimeZones() !== null],
            'output-array.js' => $this->outputArrayFailuresByAssertion(),
            'prop-desc.js' => $this->propertyDescriptorFailuresByAssertion(),
            default => [],
        };
    }

    /** @return list<bool> */
    private function brandingFailuresByAssertion(): array
    {
        $reflection = new \ReflectionClass(Locale::class);
        if (!$reflection->hasMethod('getTimeZones')) {
            return array_fill(0, 10, true);
        }

        $uninitialized = $reflection->newInstanceWithoutConstructor();
        /** @var list<mixed> $receivers */
        $receivers = [null, null, true, '', 'Symbol()', 1, new \stdClass(), Locale::class, $uninitialized];
        $failures = [false];
        foreach ($receivers as $receiver) {
            try {
                self::invokeWithReceiver($receiver);
                $failures[] = true;
            } catch (TypeError) {
                $failures[] = false;
            }
        }

        return $failures;
    }

    /** @return list<bool> */
    private function outputArrayFailuresByAssertion(): array
    {
        $output = (new Locale('en-US'))->getTimeZones();

        return [!is_array($output), $output === []];
    }

    /** @return list<bool> */
    private function propertyDescriptorFailuresByAssertion(): array
    {
        $reflection = new \ReflectionClass(Locale::class);
        if (!$reflection->hasMethod('getTimeZones')) {
            return [true, true];
        }
        $method = $reflection->getMethod('getTimeZones');

        return [false, !$method->isPublic() || $method->isStatic()];
    }

    private static function invokeWithReceiver(mixed $receiver): void
    {
        if (!$receiver instanceof Locale) {
            throw new TypeError('Locale receiver is not initialized.');
        }

        $receiver->getTimeZones();
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
            'output-array-undefined.js' => ['ECMAScript undefined is represented by PHP null.'],
            'prop-desc.js' => $index === 0
                ? ['ECMAScript typeof function is represented by an existing PHP instance method.']
                : [
                    'The ECMAScript prototype data property is represented by a public, non-static PHP method.',
                    'ECMAScript writable, enumerable, and configurable flags have no PHP method-metadata counterparts.',
                ],
            default => ['The ECMAScript Array is represented by a PHP list array.'],
        };
    }

    /** @param list<string>|null $identifiers */
    private static function isSorted(?array $identifiers): bool
    {
        if ($identifiers === null) {
            return false;
        }
        $sorted = $identifiers;
        sort($sorted, SORT_STRING);

        return $identifiers === $sorted;
    }

    private function render(string $name, string $fixturePath): string
    {
        $body = match ($name) {
            'branding.js' => <<<'PHP'
                Assert::assertTrue(method_exists(Locale::class, 'getTimeZones'));
                $uninitialized = (new ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
                $receivers = [null, null, true, '', 'Symbol()', 1, new stdClass(), Locale::class, $uninitialized];
                $invoke = static function (mixed $receiver): void {
                    if (!$receiver instanceof Locale) {
                        throw new TypeError('Locale receiver is not initialized.');
                    }
                    $receiver->getTimeZones();
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
                PHP,
            'name.js'
                => "Assert::assertSame('getTimeZones', (new ReflectionMethod(Locale::class, 'getTimeZones'))->getName());",
            'output-array-sorted.js' => <<<'PHP'
                $output = (new Locale('en-US'))->getTimeZones();
                $sorted = $output;
                sort($sorted, SORT_STRING);
                Assert::assertSame($sorted, $output);
                PHP,
            'output-array-undefined.js' => "Assert::assertNull((new Locale('en'))->getTimeZones());",
            'output-array.js' => <<<'PHP'
                $output = (new Locale('en-US'))->getTimeZones();
                Assert::assertIsArray($output);
                Assert::assertNotEmpty($output);
                PHP,
            'prop-desc.js' => <<<'PHP'
                Assert::assertTrue(method_exists(Locale::class, 'getTimeZones'));
                $method = new ReflectionMethod(Locale::class, 'getTimeZones');
                Assert::assertTrue($method->isPublic() && !$method->isStatic());
                PHP,
            default => throw new \LogicException('Unsupported getTimeZones fixture.'),
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
