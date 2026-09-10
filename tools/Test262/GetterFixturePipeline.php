<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class GetterFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        try {
            return $this->translate($source, $fixturePath, $identities);
        } catch (TranslationGap $gap) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                $gap,
            );
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
                .'(?<expected>undefined|"[^"]*"|\'[^\']*\')\);/',
                $case['body'],
                $matches,
                PREG_SET_ORDER,
            );

            $expected = [];
            $caseAssertionIndexes = [];
            foreach ($matches as $assertion) {
                $identity = $identities[$assertionIndex] ?? throw new TranslationGap(
                    'A getter assertion has no source identity.',
                );
                if ($identity['call'] !== 'assert.sameValue') {
                    throw new TranslationGap('The fixture contains an unsupported assertion construct.');
                }
                $caseAssertionIndexes[] = $assertionIndex;
                ++$assertionIndex;
                $property = $assertion['property'];
                $value = $assertion['expected'] === 'undefined'
                    ? null
                    : substr($assertion['expected'], 1, -1);

                if ($property === 'variants') {
                    $assertions[] = [
                        ...$identity,
                        'status' => 'translation_gap',
                        'reason' => 'The variants property is outside the initial language/script/region slice.',
                    ];
                    continue;
                }

                $expected[$property] = $value;
                $assertions[] = [
                    ...$identity,
                    'status' => 'passing',
                    'phpRepresentations' => ['direct'],
                ];
            }

            if (!str_contains($case['tag'], '-1901')) {
                $rows[$case['tag']] = $expected;
                continue;
            }

            foreach ($caseAssertionIndexes as $index) {
                $assertions[$index] = [
                    ...$identities[$index],
                    'status' => 'translation_gap',
                    'reason' => 'Variants in the input identifier are outside the initial slice.',
                ];
            }
        }

        if ($assertionIndex !== count($identities)) {
            throw new TranslationGap('A getter source assertion was not translated or classified.');
        }

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            'partially_translated',
            ['direct'],
            array_values($assertions),
            count(array_filter(
                $assertions,
                static fn (array $assertion): bool => $assertion['status'] === 'passing',
            )),
            0,
            ['tests/Test262/Generated/GettersMissingTest.php' => $this->render($rows, $fixturePath)],
            'Assertions that require variant support remain translation gaps.',
        );
    }

    /** @param array<string, array<string, string|null>> $rows */
    private function render(array $rows, string $fixturePath): string
    {
        $export = preg_replace('/[ \t]+$/m', '', var_export($rows, true));
        if ($export === null) {
            throw new \RuntimeException('Unable to format the generated getter cases.');
        }

        return <<<PHP
<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GettersMissingTest extends TestCase
{
    /** @return list<array{string, array<string, string|null>}> */
    public static function cases(): array
    {
        return array_map(
            static fn (array \$expected, string \$tag): array => [\$tag, \$expected],
            {$export},
            array_keys({$export}),
        );
    }

    /** @param array<string, string|null> \$expected */
    #[DataProvider('cases')]
    public function testTranslatedGetterAssertions(string \$tag, array \$expected): void
    {
        \$locale = new Locale(\$tag);

        foreach (\$expected as \$property => \$value) {
            self::assertSame(\$value, \$locale->{\$property});
        }
    }
}
PHP;
    }
}
