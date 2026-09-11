<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262;

use Midnight\Intl\Tools\Test262\GeneratedScriptCatalog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RunnerTest extends TestCase
{
    /** @return iterable<string, array{string}> */
    public static function scripts(): iterable
    {
        $catalog = new GeneratedScriptCatalog(dirname(__DIR__, 2), __DIR__.'/evidence.json');
        foreach ($catalog->scripts() as $identity => $scriptPath) {
            yield $identity => [$scriptPath];
        }
    }

    #[DataProvider('scripts')]
    public function testScript(string $scriptPath): void
    {
        require $scriptPath;
    }
}
