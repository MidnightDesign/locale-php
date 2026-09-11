<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;

final class TextInfoFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $kind,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {
        if (!in_array($kind, ['keys', 'record'], true)) {
            throw new \InvalidArgumentException(sprintf('Unsupported text-information fixture kind "%s".', $kind));
        }
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $constructs = $this->assertionIdentities->extractConstructs($source, $fixturePath);
        $classifications = array_map($this->classify(...), $constructs);
        $expectedClassifications = $this->kind === 'keys' ? ['direction', 'keys', 'property-descriptor'] : ['record'];
        $actualClassifications = $classifications;
        sort($actualClassifications);
        if ($actualClassifications !== $expectedClassifications) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['associative_array'],
                array_column($constructs, 'identity'),
                new TranslationGap('The text-information fixture has an unexpected assertion shape.'),
            );
        }

        $result = (new \ReflectionMethod(Locale::class, 'getTextInfo'))->invoke(new Locale('en'));
        $assertions = [];
        $failures = 0;
        foreach ($constructs as $index => $construct) {
            $classification = $classifications[$index];
            $inapplicableDescriptor = $classification === 'property-descriptor';
            $passing = match ($classification) {
                'record' => is_array($result),
                'keys' => is_array($result) && array_keys($result) === ['direction'],
                'direction' => is_array($result) && in_array($result['direction'] ?? null, ['ltr', 'rtl'], true),
                'property-descriptor' => true,
                default => false,
            };
            if (!$inapplicableDescriptor && !$passing) {
                ++$failures;
            }
            $assertions[] = [
                ...$construct['identity'],
                'status' => $inapplicableDescriptor ? 'inapplicable' : ($passing ? 'passing' : 'failing'),
                'adaptations' => [
                    $inapplicableDescriptor
                        ? 'JavaScript property descriptor flags have no faithful ordinary PHP equivalent and are inapplicable.'
                        : (
                            $this->kind === 'record'
                                ? 'A PHP associative array represents the ordinary ECMAScript result record.'
                                : 'The PHP associative record preserves the exact direction key and closed string result.'
                        ),
                ],
            ];
        }

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures > 0 ? 'failing' : ($this->kind === 'keys' ? 'partially_translated' : 'passing'),
            ['associative_array'],
            $assertions,
            $this->kind === 'keys' ? 2 : 1,
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($fixturePath))],
            $this->kind === 'keys'
                ? 'JavaScript property descriptor flags have no faithful ordinary PHP equivalent; the exact key and direction assertions run.'
                : null,
        );
    }

    /**
     * @param array{
     *     identity: array{id: string, line: int, column: int, call: string, sha256: string},
     *     source: string
     * } $construct
     */
    private function classify(array $construct): string
    {
        $patterns = [
            'keys' => "~^assert\\s*\\.\\s*compareArray\\s*\\(\\s*Reflect\\s*\\.\\s*ownKeys\\s*\\(\\s*result\\s*\\)\\s*,\\s*\\[\\s*'direction'\\s*]\\s*\\)$~",
            'property-descriptor' => "~^verifyProperty\\s*\\(\\s*result\\s*,\\s*'direction'\\s*,\\s*\\{\\s*writable\\s*:\\s*true\\s*,\\s*enumerable\\s*:\\s*true\\s*,\\s*configurable\\s*:\\s*true\\s*}\\s*\\)$~",
            'direction' => "~^assert\\s*\\(\\s*direction\\s*===\\s*'rtl'\\s*\\|\\|\\s*direction\\s*===\\s*'ltr'\\s*,\\s*'value of the `direction` property'\\s*\\)$~",
            'record' => "~^assert\\s*\\.\\s*sameValue\\s*\\(\\s*Object\\s*\\.\\s*getPrototypeOf\\s*\\(\\s*new\\s+Intl\\s*\\.\\s*Locale\\s*\\(\\s*'en'\\s*\\)\\s*\\.\\s*getTextInfo\\s*\\(\\s*\\)\\s*\\)\\s*,\\s*Object\\s*\\.\\s*prototype\\s*\\)$~",
        ];
        foreach ($patterns as $classification => $pattern) {
            if (preg_match($pattern, $construct['source']) === 1) {
                return $classification;
            }
        }

        return 'unknown';
    }

    private function render(string $fixturePath): string
    {
        $assertions = $this->kind === 'keys' ? <<<'PHP'
                Assert::assertSame(['direction'], array_keys($result));
                Assert::assertContains($result['direction'], ['ltr', 'rtl']);
                PHP : 'Assert::assertIsArray($result);';

        return <<<PHP
            <?php

            declare(strict_types=1);

            // Copyright 2021 Igalia, S.L. All rights reserved.
            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            \$result = (new Locale('en'))->getTextInfo();

            {$assertions}
            PHP . "\n";
    }
}
