<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use Midnight\Intl\Tools\Test262\GeneratedOutputPublisher;
use PHPUnit\Framework\TestCase;

final class Test262GeneratedOutputPublisherTest extends TestCase
{
    public function testPublicationReplacesTheGeneratedTreeAndEvidenceTogether(): void
    {
        $root = PackageSmoke::temporaryDirectory('locale-test262-publisher');

        try {
            self::write($root.'/tests/Test262/Generated/stale.php', "stale\n");
            self::write($root.'/tests/Test262/evidence.json', "old evidence\n");

            $evidence = json_encode(['fixtures' => [[
                'path' => 'test/example.js',
                'status' => 'passing',
                'generatedScripts' => [[
                    'path' => 'tests/Test262/Generated/test/example.php',
                    'identity' => 'test/example.js',
                    'variant' => null,
                ]],
            ]]], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
            (new GeneratedOutputPublisher($root))->publish([
                'tests/Test262/Generated/test/example.php' => "<?php\n",
                'tests/Test262/evidence.json' => $evidence,
            ]);

            self::assertFileDoesNotExist($root.'/tests/Test262/Generated/stale.php');
            self::assertSame("<?php\n", file_get_contents($root.'/tests/Test262/Generated/test/example.php'));
            self::assertSame($evidence, file_get_contents($root.'/tests/Test262/evidence.json'));
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testInvalidOutputCannotPartiallyModifyExistingFiles(): void
    {
        $root = PackageSmoke::temporaryDirectory('locale-test262-publisher');

        try {
            self::write($root.'/tests/Test262/Generated/existing.php', "existing\n");

            try {
                (new GeneratedOutputPublisher($root))->publish([
                    'tests/Test262/Generated/replacement.php' => "replacement\n",
                    'tests/Test262/evidence.json' => "new evidence\n",
                    '../outside.php' => "invalid\n",
                ]);
                self::fail('Expected the invalid publication to fail.');
            } catch (\InvalidArgumentException) {
                self::assertSame("existing\n", file_get_contents($root.'/tests/Test262/Generated/existing.php'));
                self::assertFileDoesNotExist($root.'/tests/Test262/Generated/replacement.php');
            }
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testMalformedStagedScriptCannotReplaceExistingOutput(): void
    {
        $root = PackageSmoke::temporaryDirectory('locale-test262-publisher');

        try {
            self::write($root.'/tests/Test262/Generated/existing.php', "existing\n");
            self::write($root.'/tests/Test262/evidence.json', "existing evidence\n");
            $evidence = json_encode(['fixtures' => [[
                'path' => 'test/example.js',
                'status' => 'passing',
                'generatedScripts' => [[
                    'path' => 'tests/Test262/Generated/test/example.php',
                    'identity' => 'test/example.js',
                    'variant' => null,
                ]],
            ]]], JSON_THROW_ON_ERROR);

            try {
                (new GeneratedOutputPublisher($root))->publish([
                    'tests/Test262/Generated/test/example.php' => "<?php this is invalid PHP\n",
                    'tests/Test262/evidence.json' => $evidence,
                ]);
                self::fail('Expected malformed staged PHP to fail validation.');
            } catch (\RuntimeException $error) {
                self::assertStringContainsString('failed syntax validation', $error->getMessage());
                self::assertSame("existing\n", file_get_contents($root.'/tests/Test262/Generated/existing.php'));
                self::assertSame("existing evidence\n", file_get_contents($root.'/tests/Test262/evidence.json'));
            }
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    private static function write(string $path, string $contents): void
    {
        if (!is_dir(dirname($path))) {
            self::assertTrue(mkdir(dirname($path), 0700, true));
        }
        self::assertNotFalse(file_put_contents($path, $contents));
    }
}
