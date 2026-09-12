<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Internal\Data\CollationAvailability;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CollationAvailability::class)]
final class CollationAvailabilityTest extends TestCase
{
    public function testThePinnedProjectionIsCompleteAndInternallyConsistent(): void
    {
        CollationAvailability::assertIntegrity();

        self::assertSame('48.2', CollationAvailability::CLDR_VERSION);
        $source = file_get_contents(dirname(__DIR__, 2) . '/resources/data/collations.json');
        self::assertNotFalse($source);
        self::assertSame(CollationAvailability::SOURCE_SHA256, hash('sha256', $source));
        /** @var array{sourceEntries: array<string, string>, root: list<string>, locales: array<string, list<string>>} $projection */
        $projection = json_decode($source, true, flags: JSON_THROW_ON_ERROR);

        self::assertCount(137, $projection['sourceEntries']);
        self::assertSame(['emoji', 'eor'], $projection['root']);
        self::assertCount(16, $projection['locales']);
        self::assertSame($projection['root'], CollationAvailability::ROOT);
        self::assertSame($projection['locales'], CollationAvailability::LOCALES);
    }
}
