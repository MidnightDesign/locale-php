<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\MagoFormatter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MagoFormatter::class)]
final class MagoFormatterTest extends TestCase
{
    public function testItFormatsGeneratedPhpUsingTheProjectConfiguration(): void
    {
        $source = '<?php final class Example{public function value():int{return 1;}}';

        self::assertSame(<<<'PHP'
            <?php

            final class Example
            {
                public function value(): int
                {
                    return 1;
                }
            }
            PHP . "\n", MagoFormatter::format(dirname(__DIR__, 2), 'build/Example.php', $source));
    }

    public function testItFormatsMultipleGeneratedFilesInOneBatch(): void
    {
        $sources = [
            'build/First.php' => '<?php final class First{public function value():int{return 1;}}',
            'build/Second.php' => '<?php final class Second{public function value():int{return 2;}}',
        ];

        $formatted = MagoFormatter::formatAll(dirname(__DIR__, 2), $sources);

        self::assertSame(array_keys($sources), array_keys($formatted));
        self::assertStringContainsString("final class First\n{", $formatted['build/First.php']);
        self::assertStringContainsString("final class Second\n{", $formatted['build/Second.php']);
    }
}
