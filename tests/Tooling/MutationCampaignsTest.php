<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\MutationCampaigns;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(MutationCampaigns::class)]
final class MutationCampaignsTest extends TestCase
{
    #[DataProvider('porcelainSourceProvider')]
    public function testItAssignsPublicValueTypesToThePorcelainCampaign(string $source): void
    {
        self::assertSame('porcelain', MutationCampaigns::forSource($source));
    }

    /** @return iterable<string, array{string}> */
    public static function porcelainSourceProvider(): iterable
    {
        yield 'locale facade' => ['src/Locale.php'];
        yield 'text direction enum' => ['src/TextDirection.php'];
        yield 'text information value object' => ['src/TextInfo.php'];
    }
}
