<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tools\PhpExporter;

final class GetterFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        try {
            return $this->translate($source, $fixturePath, $identities);
        } catch (TranslationGap $gap) {
            return FixtureResult::translationGap($fixturePath, $source, ['direct'], $identities, $gap);
        }
    }

    /**
     * @param list<array{id: string, line: int, column: int, call: string, sha256: string}> $identities
     */
    private function translate(string $source, string $fixturePath, array $identities): FixtureResult
    {
        preg_match_all(
            '/var loc = new Intl\.Locale\("(?<tag>[^"]+)"\);(?<body>.*?)(?=\nvar loc =|\z)/s',
            $source,
            $cases,
            PREG_SET_ORDER,
        );

        $rows = [];
        $assertions = [];
        $assertionIndex = 0;
        foreach ($cases as $case) {
            preg_match_all(
                '/assert\.sameValue\(loc\.(?<property>baseName|language|script|region|variants),\s*'
                . '(?<expected>undefined|"[^"]*"|\'[^\']*\')\);/',
                $case['body'],
                $matches,
                PREG_SET_ORDER,
            );

            $expected = [];
            foreach ($matches as $assertion) {
                $identity = $identities[$assertionIndex] ?? throw new TranslationGap(
                    'A getter assertion has no source identity.',
                );
                if ($identity['call'] !== 'assert.sameValue') {
                    throw new TranslationGap('The fixture contains an unsupported assertion construct.');
                }
                ++$assertionIndex;
                $property = $assertion['property'];
                $value = $assertion['expected'] === 'undefined' ? null : substr($assertion['expected'], 1, -1);

                $expected[$property] = $value;
                $assertions[] = [
                    ...$identity,
                    'status' => 'passing',
                    'phpRepresentations' => ['direct'],
                ];
            }

            $rows[$case['tag']] = $expected;
        }

        if ($assertionIndex !== count($identities)) {
            throw new TranslationGap('A getter source assertion was not translated or classified.');
        }

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            'passing',
            ['direct'],
            $assertions,
            count($assertions),
            0,
            [GeneratedScript::primary($fixturePath, $this->render($rows, $fixturePath))],
        );
    }

    /** @param array<string, array<string, string|null>> $rows */
    private function render(array $rows, string $fixturePath): string
    {
        $export = preg_replace('/[ \t]+$/m', '', PhpExporter::export($rows));
        if ($export === null) {
            throw new \RuntimeException('Unable to format the generated getter cases.');
        }

        $generated = <<<PHP
            <?php

            declare(strict_types=1);

            // Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Assert;

            foreach ({$export} as \$tag => \$expected) {
                \$locale = new Locale(\$tag);

                foreach (\$expected as \$property => \$value) {
                    Assert::assertSame(\$value, \$locale->{\$property});
                }
            }
            PHP;

        return $generated . "\n";
    }
}
