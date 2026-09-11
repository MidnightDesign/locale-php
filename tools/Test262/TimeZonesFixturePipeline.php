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
        $expectedAssertions = match (basename($fixturePath)) {
            'branding.js' => 10,
            'name.js', 'output-array-sorted.js', 'output-array-undefined.js' => 1,
            'output-array.js', 'prop-desc.js' => 2,
            default => 0,
        };
        if ($expectedAssertions === 0 || count($identities) !== $expectedAssertions) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap('The getTimeZones fixture shape is not supported.'),
            );
        }

        $failures = $this->executionFailures(basename($fixturePath));
        $assertions = array_map(static fn(array $identity): array => [
            ...$identity,
            'status' => $failures === 0 ? 'passing' : 'failing',
            'adaptations' => [
                'ECMAScript undefined is represented by PHP null.',
                'ECMAScript built-in branding and descriptors are represented by the public PHP method and its initialized-receiver check.',
            ],
        ], $identities);

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['direct'],
            $assertions,
            $expectedAssertions,
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render(basename($fixturePath), $fixturePath))],
        );
    }

    private function executionFailures(string $name): int
    {
        return match ($name) {
            'branding.js' => $this->brandingFailures(),
            'name.js' => (new \ReflectionMethod(Locale::class, 'getTimeZones'))->getName() === 'getTimeZones' ? 0 : 1,
            'output-array-sorted.js' => self::isSorted((new Locale('en-US'))->getTimeZones()) ? 0 : 1,
            'output-array-undefined.js' => (new Locale('en'))->getTimeZones() === null ? 0 : 1,
            'output-array.js' => is_array((new Locale('en-US'))->getTimeZones())
                && (new Locale('en-US'))->getTimeZones() !== []
                    ? 0
                    : 1,
            'prop-desc.js' => (new \ReflectionMethod(Locale::class, 'getTimeZones'))->isPublic() ? 0 : 1,
            default => 1,
        };
    }

    private function brandingFailures(): int
    {
        $methodNames = array_map(
            static fn(\ReflectionMethod $method): string => $method->getName(),
            (new \ReflectionClass(Locale::class))->getMethods(),
        );
        if (!in_array('getTimeZones', $methodNames, true)) {
            return 10;
        }
        $failures = 0;
        for ($index = 0; $index < 9; ++$index) {
            $locale = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
            try {
                $locale->getTimeZones();
                ++$failures;
            } catch (TypeError) {
            } catch (\Throwable) {
                ++$failures;
            }
        }

        return $failures;
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
                for ($index = 0; $index < 9; ++$index) {
                    $locale = (new ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
                    $rejected = false;
                    try {
                        $locale->getTimeZones();
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
                $method = new ReflectionMethod(Locale::class, 'getTimeZones');
                Assert::assertSame('getTimeZones', $method->getName());
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
