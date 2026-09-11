<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use Midnight\Intl\Tools\PhpExporter;

final class LikelySubtagsPipeline implements FixturePipeline
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
        $maximal = JavaScriptDataExtractor::objectMap($source, 'testDataMaximal');
        $minimal = JavaScriptDataExtractor::objectMap($source, 'testDataMinimal');
        $extras = JavaScriptDataExtractor::stringArray($source, 'extras');
        if (count($identities) !== 5 || $maximal === [] || $minimal === [] || $extras === []) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap('Expected the likely-subtag maps, extras, and five source assertions.'),
            );
        }

        $failures = 0;
        foreach ($maximal as $tag => $expected) {
            $failures += (new Locale($expected))->maximize()->toString() === $expected ? 0 : 1;
            foreach ($extras as $extra) {
                $failures += (new Locale($tag.$extra))->maximize()->toString() === $expected.$extra ? 0 : 1;
            }
        }
        foreach ($minimal as $tag => $expected) {
            $failures += (new Locale($expected))->minimize()->toString() === $expected ? 0 : 1;
            foreach ($extras as $extra) {
                $failures += (new Locale($tag.$extra))->minimize()->toString() === $expected.$extra ? 0 : 1;
            }
        }
        try {
            new Locale('x-private');
            ++$failures;
        } catch (RangeError) {
        }

        $status = $failures === 0 ? 'passing' : 'failing';

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $status,
            ['direct'],
            array_map(static fn (array $identity): array => [
                ...$identity,
                'status' => $status,
                'adaptations' => ['JavaScript object and array iteration is expanded into named PHPUnit data sets.'],
            ], $identities),
            count($maximal) * (count($extras) + 1) + count($minimal) * (count($extras) + 1) + 1,
            $failures,
            ['tests/Test262/Generated/LikelySubtagsTest.php' => $this->render($maximal, $minimal, $extras, $fixturePath)],
        );
    }

    /**
     * @param array<string, string> $maximal
     * @param array<string, string> $minimal
     * @param list<string>          $extras
     */
    private function render(array $maximal, array $minimal, array $extras, string $fixturePath): string
    {
        $maximalExport = self::export($maximal);
        $minimalExport = self::export($minimal);
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

final class LikelySubtagsTest extends TestCase
{
    /** @return iterable<string, array{string, string}> */
    public static function maximalCases(): iterable
    {
        foreach ({$maximalExport} as \$tag => \$maximal) {
            yield \$tag.' maximal fixed point' => [\$maximal, \$maximal];
            foreach ({$extrasExport} as \$extra) {
                yield \$tag.\$extra => [\$tag.\$extra, \$maximal.\$extra];
            }
        }
    }

    /** @return iterable<string, array{string, string}> */
    public static function minimalCases(): iterable
    {
        foreach ({$minimalExport} as \$tag => \$minimal) {
            yield \$tag.' minimal fixed point' => [\$minimal, \$minimal];
            foreach ({$extrasExport} as \$extra) {
                yield \$tag.\$extra => [\$tag.\$extra, \$minimal.\$extra];
            }
        }
    }

    #[DataProvider('maximalCases')]
    public function testTranslatedMaximizeAssertions(string \$tag, string \$expected): void
    {
        self::assertSame(\$expected, (new Locale(\$tag))->maximize()->toString());
    }

    #[DataProvider('minimalCases')]
    public function testTranslatedMinimizeAssertions(string \$tag, string \$expected): void
    {
        self::assertSame(\$expected, (new Locale(\$tag))->minimize()->toString());
    }

    public function testTranslatedPrivateUseRejectionAssertion(): void
    {
        \$this->expectException(RangeError::class);

        new Locale('x-private');
    }
}
PHP."\n";
    }

    /** @param array<array-key, mixed> $value */
    private static function export(array $value): string
    {
        return preg_replace('/[ \t]+$/m', '', PhpExporter::export($value))
            ?? throw new \RuntimeException('Unable to format likely-subtag cases.');
    }
}
