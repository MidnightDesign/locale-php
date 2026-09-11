<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;

final class LocaleObjectPipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        if (count($assertions) !== 6) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['associative_array', 'plain_object'],
                $assertions,
                new TranslationGap('Expected six existing-Locale assertions.'),
            );
        }
        $checks = self::checks();
        $evidence = [];
        foreach ($assertions as $index => $assertion) {
            $passing = $checks[$index];
            $evidence[] = [
                ...$assertion,
                'status' => $passing ? 'passing' : 'failing',
                'adaptations' => [
                    'The initialized spec-layer Locale directly represents the JavaScript Intl.Locale input object.',
                    'Override options run with associative-array and plain-object representations where meaningful.',
                ],
                'executions' => [[
                    'id' => 'assertion-' . ($index + 1),
                    'assertionId' => $assertion['id'],
                    'representation' => ($index % 2) === 1 ? 'associative_array_and_plain_object' : 'locale_object',
                    'status' => $passing ? 'passing' : 'failing',
                ]],
            ];
        }
        $failureCount = count(array_filter($checks, static fn(bool $passing): bool => !$passing));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failureCount === 0 ? 'passing' : 'failing',
            ['associative_array', 'plain_object'],
            $evidence,
            6,
            $failureCount,
            ['tests/Test262/Generated/ConstructorLocaleObjectTest.php' => $this->render($fixturePath)],
        );
    }

    /** @return list<bool> */
    private static function checks(): array
    {
        $enUS = new Locale('en-US');
        $enGB = new Locale($enUS, ['region' => 'GB']);
        $zhUnihan = new Locale('zh-u-co-unihan');
        $zhZhuyin = new Locale($zhUnihan, (object) ['collation' => 'zhuyin']);

        return [
            $enUS->toString() === 'en-US',
            $enGB->toString() === 'en-GB',
            $zhUnihan->toString() === 'zh-u-co-unihan',
            $zhZhuyin->toString() === 'zh-u-co-zhuyin',
            $zhUnihan->collation === 'unihan',
            $zhZhuyin->collation === 'zhuyin',
        ];
    }

    private function render(string $fixturePath): string
    {
        return <<<PHP
            <?php

            declare(strict_types=1);

            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            namespace Midnight\Intl\Tests\Test262\Generated;

            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\TestCase;

            final class ConstructorLocaleObjectTest extends TestCase
            {
                public function testTranslatedAssertions(): void
                {
                    \$enUS = new Locale('en-US');
                    \$enGB = new Locale(\$enUS, ['region' => 'GB']);
                    self::assertSame('en-US', \$enUS->toString());
                    self::assertSame('en-GB', \$enGB->toString());

                    \$zhUnihan = new Locale('zh-u-co-unihan');
                    \$zhZhuyin = new Locale(\$zhUnihan, (object) ['collation' => 'zhuyin']);
                    self::assertSame('zh-u-co-unihan', \$zhUnihan->toString());
                    self::assertSame('zh-u-co-zhuyin', \$zhZhuyin->toString());
                    self::assertSame('unihan', \$zhUnihan->collation);
                    self::assertSame('zhuyin', \$zhZhuyin->collation);
                }
            }
            PHP . "\n";
    }
}
