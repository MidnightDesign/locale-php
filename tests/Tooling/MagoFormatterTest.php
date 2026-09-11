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
}
