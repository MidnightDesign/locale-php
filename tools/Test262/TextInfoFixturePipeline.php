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
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $expectedAssertions = $this->kind === 'keys' ? 3 : 1;
        if (count($identities) !== $expectedAssertions) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['associative_array'],
                $identities,
                new TranslationGap(sprintf('Expected %d text-information assertions.', $expectedAssertions)),
            );
        }

        $result = (new \ReflectionMethod(Locale::class, 'getTextInfo'))->invoke(new Locale('en'));
        $failures = is_array($result) ? 0 : $expectedAssertions;
        if ($this->kind === 'keys' && is_array($result)) {
            $failures += array_keys($result) === ['direction'] ? 0 : 1;
            $failures += in_array($result['direction'] ?? null, ['ltr', 'rtl'], true) ? 0 : 1;
        }

        $assertions = [];
        foreach ($identities as $index => $identity) {
            $inapplicableDescriptor = $this->kind === 'keys' && $index === 1;
            $assertions[] = [
                ...$identity,
                'status' => $failures > 0 ? 'failing' : ($inapplicableDescriptor ? 'inapplicable' : 'passing'),
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
