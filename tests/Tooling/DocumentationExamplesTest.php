<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use Midnight\Intl\Tools\DocumentationExamples;
use PHPUnit\Framework\TestCase;

final class DocumentationExamplesTest extends TestCase
{
    public function testPublicDocumentationExamplesRun(): void
    {
        DocumentationExamples::checkPublic(dirname(__DIR__, 2));

        $this->addToAssertionCount(1);
    }

    public function testPublicDocumentationExamplesRunIndependently(): void
    {
        $root = self::documentationFixture([
            'README.md' => <<<'MARKDOWN'
                ```php
                $shared = true;
                file_put_contents('first-example-ran', '');
                ```

                ```php
                assert(!isset($shared));
                file_put_contents('second-example-ran', '');
                ```
                MARKDOWN,
        ]);

        try {
            DocumentationExamples::checkPublic($root);

            self::assertFileExists($root . DIRECTORY_SEPARATOR . 'first-example-ran');
            self::assertFileExists($root . DIRECTORY_SEPARATOR . 'second-example-ran');
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testPublicDocumentationCheckRejectsEmptyExampleSet(): void
    {
        $root = self::documentationFixture([]);

        try {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('No runnable PHP documentation examples were found.');

            DocumentationExamples::checkPublic($root);
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    public function testPublicDocumentationCheckReportsFailingExample(): void
    {
        $root = self::documentationFixture([
            'README.md' => <<<'MARKDOWN'
                ```php
                assert(false);
                ```
                MARKDOWN,
        ]);

        try {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage('Documentation examples from README.md failed');

            DocumentationExamples::checkPublic($root);
        } finally {
            PackageSmoke::removeDirectory($root);
        }
    }

    /** @param array<string, string> $contents */
    private static function documentationFixture(array $contents): string
    {
        $root = PackageSmoke::temporaryDirectory('intl-locale-documentation-test');
        foreach (['docs', 'vendor'] as $directory) {
            if (!mkdir($root . DIRECTORY_SEPARATOR . $directory, 0700)) {
                throw new \RuntimeException(sprintf('Unable to create fixture directory %s.', $directory));
            }
        }

        $paths = [
            'README.md',
            'docs/getting-started.md',
            'docs/api-reference.md',
            'docs/spec-layer.md',
            'docs/migration.md',
        ];
        foreach ($paths as $path) {
            $absolutePath = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
            file_put_contents($absolutePath, $contents[$path] ?? '');
        }
        file_put_contents($root . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php', "<?php\n");

        return $root;
    }
}
