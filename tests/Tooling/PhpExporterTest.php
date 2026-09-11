<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\PhpExporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PhpExporter::class)]
final class PhpExporterTest extends TestCase
{
    public function testItFormatsSyntaxWithoutChangingStringValues(): void
    {
        self::assertSame("array(\n  'value' => null,\n  'literal' => 'array (NULL)',\n)", PhpExporter::export([
            'value' => null,
            'literal' => 'array (NULL)',
        ]));
    }
}
