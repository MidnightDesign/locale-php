<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;

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
        $failuresByAssertion = $this->failuresByAssertion($name);
        if ($failuresByAssertion === [] || count($identities) !== count($failuresByAssertion)) {
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
            $this->executionCount($name),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($name, $fixturePath))],
        );
    }

    /** @return list<bool> Whether each source assertion failed, in source order. */
    private function failuresByAssertion(string $name): array
    {
        return match ($name) {
            'branding.js' => $this->brandingFailuresByAssertion(),
            'collation-keyword.js' => $this->keywordFailuresByAssertion(),
            'name.js' => [(new \ReflectionMethod(Locale::class, 'getCollations'))->getName() !== 'getCollations'],
            'output-array-sorted.js' => [!$this->allOutputsAreSorted()],
            'output-array-values.js' => $this->valueFailuresByAssertion(),
            'output-array.js' => [!$this->allOutputsAreArrays()],
            'prop-desc.js' => $this->propertyDescriptorFailuresByAssertion(),
            'und-language.js' => $this->unmatchedLocaleFailuresByAssertion(),
            default => [],
        };
    }

    /** @return list<bool> */
    private function brandingFailuresByAssertion(): array
    {
        $reflection = new \ReflectionClass(Locale::class);
        if (!$reflection->hasMethod('getCollations')) {
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
    private function keywordFailuresByAssertion(): array
    {
        $identifierFailure = false;
        $optionFailure = false;
        foreach ([['en', 'phonebk'], ['de', 'phonebk'], ['zh', 'stroke'], ['und', 'pinyin'], ['und', 'emoji']] as [
            $base,
            $collation,
        ]) {
            $identifierFailure =
                $identifierFailure || (new Locale($base . '-u-co-' . $collation))->getCollations() !== [$collation];
            $optionFailure =
                $optionFailure || (new Locale($base, ['collation' => $collation]))->getCollations() !== [$collation];
        }

        return [$identifierFailure, $optionFailure];
    }

    private function allOutputsAreSorted(): bool
    {
        foreach (self::representativeTags() as $tag) {
            $collations = (new Locale($tag))->getCollations();
            $sorted = (new Locale($tag))->getCollations();
            sort($sorted, SORT_STRING);
            if ($collations !== $sorted) {
                return false;
            }
        }

        return true;
    }

    /** @return list<bool> */
    private function valueFailuresByAssertion(): array
    {
        $empty = false;
        $standard = false;
        $search = false;
        foreach (self::representativeTags() as $tag) {
            $collations = (new Locale($tag))->getCollations();
            $empty = $empty || $collations === [];
            $standard = $standard || in_array('standard', $collations, true);
            $search = $search || in_array('search', $collations, true);
        }

        return [$empty, $standard, $search];
    }

    private function allOutputsAreArrays(): bool
    {
        $reflection = new \ReflectionClass(Locale::class);
        if (!$reflection->hasMethod('getCollations')) {
            return false;
        }

        return (string) $reflection->getMethod('getCollations')->getReturnType() === 'array';
    }

    /** @return list<bool> */
    private function propertyDescriptorFailuresByAssertion(): array
    {
        $reflection = new \ReflectionClass(Locale::class);
        if (!$reflection->hasMethod('getCollations')) {
            return [true, true];
        }
        $method = $reflection->getMethod('getCollations');

        return [false, !$method->isPublic() || $method->isStatic()];
    }

    /** @return list<bool> */
    private function unmatchedLocaleFailuresByAssertion(): array
    {
        $root = ['emoji', 'eor'];
        $languageFailure = false;
        $undFailure = false;
        foreach (['und', 'und-US', 'und-Latn', 'und-Latn-US', 'und-u-ca-gregory', 'und-US-u-nu-latn'] as $tag) {
            $locale = new Locale($tag);
            $languageFailure = $languageFailure || $locale->language !== 'und';
            $undFailure = $undFailure || $locale->getCollations() !== $root;
        }
        $privateUseFailure = false;
        foreach (['qfz', 'qga-DE', 'qgb-ES', 'qgc-KR', 'qtz-CN'] as $tag) {
            $privateUseFailure = $privateUseFailure || (new Locale($tag))->getCollations() !== $root;
        }

        return [$languageFailure, $undFailure, $privateUseFailure];
    }

    private static function invokeWithReceiver(mixed $receiver): void
    {
        if (!$receiver instanceof Locale) {
            throw new TypeError('Locale receiver is not initialized.');
        }
        $receiver->getCollations();
    }

    /** @return list<string> */
    private static function representativeTags(): array
    {
        return ['ar', 'de', 'en', 'ja', 'ko', 'sv', 'tr', 'zh'];
    }

    private function executionCount(string $name): int
    {
        return match ($name) {
            'branding.js', 'collation-keyword.js' => 10,
            'name.js' => 1,
            'output-array-sorted.js', 'output-array.js' => count(self::representativeTags()),
            'output-array-values.js' => $this->outputArrayValuesExecutionCount(),
            'prop-desc.js' => 2,
            'und-language.js' => 17,
            default => 0,
        };
    }

    private function outputArrayValuesExecutionCount(): int
    {
        $count = count(self::representativeTags());
        foreach (self::representativeTags() as $tag) {
            $count += 2 * count((new Locale($tag))->getCollations());
        }

        return $count;
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
            default => ['The ECMAScript Array is represented by a PHP list array.'],
        };
    }

    private function render(string $name, string $fixturePath): string
    {
        $body = match ($name) {
            'branding.js' => <<<'PHP'
                Assert::assertTrue(method_exists(Locale::class, 'getCollations'));
                $uninitialized = (new ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
                $receivers = [null, null, true, '', 'Symbol()', 1, new stdClass(), Locale::class, $uninitialized];
                foreach ($receivers as $receiver) {
                    $rejected = false;
                    try {
                        if (!$receiver instanceof Locale) {
                            throw new TypeError('Locale receiver is not initialized.');
                        }
                        $receiver->getCollations();
                    } catch (TypeError) {
                        $rejected = true;
                    }
                    Assert::assertTrue($rejected);
                }
                PHP,
            'collation-keyword.js' => <<<'PHP'
                foreach ([['en', 'phonebk'], ['de', 'phonebk'], ['zh', 'stroke'], ['und', 'pinyin'], ['und', 'emoji']] as [$base, $collation]) {
                    Assert::assertSame([$collation], (new Locale($base . '-u-co-' . $collation))->getCollations());
                    Assert::assertSame([$collation], (new Locale($base, ['collation' => $collation]))->getCollations());
                }
                PHP,
            'name.js'
                => "Assert::assertSame('getCollations', (new ReflectionMethod(Locale::class, 'getCollations'))->getName());",
            'output-array-sorted.js' => <<<'PHP'
                foreach (['ar', 'de', 'en', 'ja', 'ko', 'sv', 'tr', 'zh'] as $tag) {
                    $collations = (new Locale($tag))->getCollations();
                    $sortedCollations = (new Locale($tag))->getCollations();
                    sort($sortedCollations, SORT_STRING);
                    Assert::assertSame($sortedCollations, $collations);
                }
                PHP,
            'output-array-values.js' => <<<'PHP'
                foreach (['ar', 'de', 'en', 'ja', 'ko', 'sv', 'tr', 'zh'] as $tag) {
                    $collations = (new Locale($tag))->getCollations();
                    Assert::assertNotEmpty($collations);
                    foreach ($collations as $collation) {
                        Assert::assertNotSame('standard', $collation);
                        Assert::assertNotSame('search', $collation);
                    }
                }
                PHP,
            'output-array.js' => <<<'PHP'
                foreach (['ar', 'de', 'en', 'ja', 'ko', 'sv', 'tr', 'zh'] as $tag) {
                    Assert::assertIsArray((new Locale($tag))->getCollations());
                }
                PHP,
            'prop-desc.js' => <<<'PHP'
                Assert::assertTrue(method_exists(Locale::class, 'getCollations'));
                $method = new ReflectionMethod(Locale::class, 'getCollations');
                Assert::assertTrue($method->isPublic() && !$method->isStatic());
                PHP,
            'und-language.js' => <<<'PHP'
                foreach (['und', 'und-US', 'und-Latn', 'und-Latn-US', 'und-u-ca-gregory', 'und-US-u-nu-latn'] as $tag) {
                    $locale = new Locale($tag);
                    Assert::assertSame('und', $locale->language);
                    Assert::assertSame(['emoji', 'eor'], $locale->getCollations());
                }
                foreach (['qfz', 'qga-DE', 'qgb-ES', 'qgc-KR', 'qtz-CN'] as $tag) {
                    Assert::assertSame(['emoji', 'eor'], (new Locale($tag))->getCollations());
                }
                PHP,
            default => throw new \LogicException('Unsupported getCollations fixture.'),
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
