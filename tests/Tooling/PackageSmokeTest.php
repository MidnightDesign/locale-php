<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PackageSmoke::class)]
final class PackageSmokeTest extends TestCase
{
    public function testItRunsTheComposerBinaryProvidedToComposerScriptsThroughPhp(): void
    {
        $previous = getenv('COMPOSER_BINARY');
        putenv('COMPOSER_BINARY=C:\\tools\\composer.phar');

        try {
            self::assertSame(
                [PHP_BINARY, 'C:\\tools\\composer.phar'],
                PackageSmoke::composerCommand(),
            );
        } finally {
            if ($previous === false) {
                putenv('COMPOSER_BINARY');
            } else {
                putenv('COMPOSER_BINARY='.$previous);
            }
        }
    }
}
