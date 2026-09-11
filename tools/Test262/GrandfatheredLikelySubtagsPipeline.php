<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use Midnight\Intl\Tools\PhpExporter;

final class GrandfatheredLikelySubtagsPipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {}

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $rejected = [
            ...JavaScriptDataExtractor::stringArray($source, 'irregularGrandfathered'),
            ...JavaScriptDataExtractor::stringArray($source, 'regularGrandfatheredWithExtLang'),
        ];
        $regular = self::regularTags($source);
        $extras = JavaScriptDataExtractor::stringArray($source, 'extras');
        if (count($identities) !== 16 || $rejected === [] || $regular === [] || $extras === []) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap(
                    'Expected the grandfathered tag lists, records, extras, and sixteen source assertions.',
                ),
            );
        }

        $failures = 0;
        foreach ($rejected as $tag) {
            try {
                new Locale($tag);
                ++$failures;
            } catch (RangeError) {
            }
        }
        foreach ($regular as [$tag, $canonical, $maximal, $minimal]) {
            $failures += self::regularFailures(new Locale($tag), $canonical, $maximal, $minimal);
            foreach ($extras as $extra) {
                $failures += self::regularFailures(
                    new Locale($tag . '-' . $extra),
                    $canonical . '-' . $extra,
                    $maximal . '-' . $extra,
                    $minimal . '-' . $extra,
                );
            }
        }
        $status = $failures === 0 ? 'passing' : 'failing';

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $status,
            ['direct'],
            array_values(array_map(static fn(array $identity): array => [
                ...$identity,
                'status' => $status,
                'adaptations' => [
                    'JavaScript loops and defaulted record fields are expanded into named PHPUnit data sets.',
                ],
            ], $identities)),
            count($rejected) + (count($regular) * 7 * (count($extras) + 1)),
            $failures,
            ['tests/Test262/Generated/LikelySubtagsGrandfatheredTest.php' => $this->render(
                $rejected,
                $regular,
                $extras,
                $fixturePath,
            )],
        );
    }

    /** @return list<array{string, string, string, string}> */
    private static function regularTags(string $source): array
    {
        if (preg_match('/const regularGrandfathered\s*=\s*\[(?<body>.*?)\];/s', $source, $block) !== 1) {
            return [];
        }
        preg_match_all('/\{(?<record>.*?)\}/s', $block['body'], $matches, PREG_SET_ORDER);
        $result = [];
        foreach ($matches as $match) {
            preg_match_all(
                '/(?<key>tag|canonical|maximized|minimized)\s*:\s*"(?<value>[^"]+)"/',
                $match['record'],
                $fields,
                PREG_SET_ORDER,
            );
            $record = [];
            foreach ($fields as $field) {
                $record[$field['key']] = $field['value'];
            }
            if (!isset($record['tag'], $record['canonical'])) {
                continue;
            }
            $result[] = [
                $record['tag'],
                $record['canonical'],
                $record['maximized'] ?? $record['canonical'],
                $record['minimized'] ?? $record['canonical'],
            ];
        }

        return $result;
    }

    private static function regularFailures(Locale $locale, string $canonical, string $maximal, string $minimal): int
    {
        return (
            (int) ($locale->toString() !== $canonical)
            + (int) ($locale->maximize()->toString() !== $maximal)
            + (int) ($locale->maximize()->maximize()->toString() !== $maximal)
            + (int) ($locale->minimize()->toString() !== $minimal)
            + (int) ($locale->minimize()->minimize()->toString() !== $minimal)
            + (int) ($locale->maximize()->minimize()->toString() !== $minimal)
            + (int) ($locale->minimize()->maximize()->toString() !== $maximal)
        );
    }

    /**
     * @param list<string>                              $rejected
     * @param list<array{string, string, string, string}> $regular
     * @param list<string>                              $extras
     */
    private function render(array $rejected, array $regular, array $extras, string $fixturePath): string
    {
        $rejectedExport = self::export($rejected);
        $regularExport = self::export($regular);
        $extrasExport = self::export($extras);

        return <<<PHP
            <?php

            declare(strict_types=1);

            // Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
            // This generated translation is governed by tests/Test262/upstream/LICENSE.
            // Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
            // Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

            namespace Midnight\Intl\Tests\Test262\Generated;

            use Midnight\Intl\Exception\RangeError;
            use Midnight\Intl\Spec\Locale;
            use PHPUnit\Framework\Attributes\DataProvider;
            use PHPUnit\Framework\TestCase;

            final class LikelySubtagsGrandfatheredTest extends TestCase
            {
                /** @return iterable<string, array{string}> */
                public static function rejectedTags(): iterable
                {
                    foreach ({$rejectedExport} as \$tag) {
                        yield \$tag => [\$tag];
                    }
                }

                /** @return iterable<string, array{string, string, string, string}> */
                public static function regularTags(): iterable
                {
                    foreach ({$regularExport} as \$case) {
                        yield \$case[0] => \$case;
                    }
                }

                /** @return iterable<string, array{string, string, string, string}> */
                public static function regularTagsWithExtras(): iterable
                {
                    foreach ({$regularExport} as [\$tag, \$canonical, \$maximal, \$minimal]) {
                        foreach ({$extrasExport} as \$extra) {
                            yield \$tag.' '.\$extra => [
                                \$tag.'-'.\$extra,
                                \$canonical.'-'.\$extra,
                                \$maximal.'-'.\$extra,
                                \$minimal.'-'.\$extra,
                            ];
                        }
                    }
                }

                #[DataProvider('rejectedTags')]
                public function testTranslatedGrandfatheredRejectionAssertions(string \$tag): void
                {
                    \$this->expectException(RangeError::class);
                    new Locale(\$tag);
                }

                #[DataProvider('regularTags')]
                #[DataProvider('regularTagsWithExtras')]
                public function testTranslatedGrandfatheredAssertions(
                    string \$tag,
                    string \$canonical,
                    string \$maximal,
                    string \$minimal,
                ): void {
                    \$locale = new Locale(\$tag);
                    self::assertSame(\$canonical, \$locale->toString());
                    self::assertSame(\$maximal, \$locale->maximize()->toString());
                    self::assertSame(\$maximal, \$locale->maximize()->maximize()->toString());
                    self::assertSame(\$minimal, \$locale->minimize()->toString());
                    self::assertSame(\$minimal, \$locale->minimize()->minimize()->toString());
                    self::assertSame(\$minimal, \$locale->maximize()->minimize()->toString());
                    self::assertSame(\$maximal, \$locale->minimize()->maximize()->toString());
                }
            }
            PHP . "\n";
    }

    /** @param array<array-key, mixed> $value */
    private static function export(array $value): string
    {
        return (
            preg_replace('/[ \t]+$/m', '', PhpExporter::export($value)) ?? throw new \RuntimeException(
                'Unable to format grandfathered likely-subtag cases.',
            )
        );
    }
}
