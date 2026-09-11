<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use Midnight\Intl\Tools\Test262\GeneratedScriptCatalog;
use PHPUnit\Framework\TestCase;

final class Test262GeneratedScriptCatalogTest extends TestCase
{
    public function testManifestProvidesDistinctPrimaryAndRepresentationVariantIdentities(): void
    {
        $root = PackageSmoke::temporaryDirectory('locale-test262-catalog');

        try {
            $primary = 'tests/Test262/Generated/test/intl402/Locale/example.php';
            $variant = 'tests/Test262/Generated/test/intl402/Locale/example.plain-object.php';
            self::write($root . '/' . $primary, "<?php\n");
            self::write($root . '/' . $variant, "<?php\n");
            self::writeEvidence($root, [[
                'path' => 'test/intl402/Locale/example.js',
                'status' => 'passing',
                'generatedScripts' => [
                    ['path' => $primary, 'identity' => 'test/intl402/Locale/example.js', 'variant' => null],
                    [
                        'path' => $variant,
                        'identity' => 'test/intl402/Locale/example.js [plain-object]',
                        'variant' => 'plain-object',
                    ],
                ],
            ]]);

            $catalog = new GeneratedScriptCatalog($root, $root . '/tests/Test262/evidence.json');
            self::assertSame(
                [
                    [
                        'source' => 'test/intl402/Locale/example.js',
                        'path' => $primary,
                        'identity' => 'test/intl402/Locale/example.js',
                        'variant' => null,
                    ],
                    [
                        'source' => 'test/intl402/Locale/example.js',
                        'path' => $variant,
                        'identity' => 'test/intl402/Locale/example.js [plain-object]',
                        'variant' => 'plain-object',
                    ],
                ],
                $catalog->entries(),
            );

            self::assertSame(
                [
                    'test/intl402/Locale/example.js' => $root . '/' . $primary,
                    'test/intl402/Locale/example.js [plain-object]' => $root . '/' . $variant,
                ],
                $catalog->scripts(),
            );
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testCatalogRejectsStaleFilesWithoutReadingSourceComments(): void
    {
        $root = PackageSmoke::temporaryDirectory('locale-test262-catalog');

        try {
            self::write($root . '/tests/Test262/Generated/stale.php', "<?php\n");
            self::writeEvidence($root, []);

            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('stale: tests/Test262/Generated/stale.php');

            (new GeneratedScriptCatalog($root, $root . '/tests/Test262/evidence.json'))->scripts();
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testCatalogRejectsDuplicateManifestEntries(): void
    {
        $root = PackageSmoke::temporaryDirectory('locale-test262-catalog');

        try {
            $first = 'tests/Test262/Generated/test/intl402/Locale/example.php';
            $second = 'tests/Test262/Generated/test/intl402/Locale/example.second.php';
            self::write($root . '/' . $first, "<?php\n");
            self::write($root . '/' . $second, "<?php\n");
            self::writeEvidence($root, [[
                'path' => 'test/intl402/Locale/example.js',
                'status' => 'passing',
                'generatedScripts' => [
                    [
                        'path' => $first,
                        'identity' => 'test/intl402/Locale/example.js',
                        'variant' => null,
                    ],
                    [
                        'path' => $second,
                        'identity' => 'test/intl402/Locale/example.js [second]',
                        'variant' => 'second',
                    ],
                    [
                        'path' => $second,
                        'identity' => 'test/intl402/Locale/example.js [second]',
                        'variant' => 'second',
                    ],
                ],
            ]]);

            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('path is duplicated in evidence');

            (new GeneratedScriptCatalog($root, $root . '/tests/Test262/evidence.json'))->scripts();
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    /** @param list<array{path: string, status: string, generatedScripts: list<array{path: string, identity: string, variant: string|null}>}> $fixtures */
    private static function writeEvidence(string $root, array $fixtures): void
    {
        self::write(
            $root . '/tests/Test262/evidence.json',
            json_encode(['fixtures' => $fixtures], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
                . "\n",
        );
    }

    private static function write(string $path, string $contents): void
    {
        if (!is_dir(dirname($path))) {
            self::assertTrue(mkdir(dirname($path), 0700, true));
        }
        self::assertNotFalse(file_put_contents($path, $contents));
    }
}
