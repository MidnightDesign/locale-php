<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\InventoryAudit;
use PHPUnit\Framework\TestCase;

final class Test262InventoryAuditTest extends TestCase
{
    public function testTheCommittedInventoryAccountsForEveryTranslatedAssertion(): void
    {
        $auditPath = dirname(__DIR__, 2) . '/tools/Test262/InventoryAudit.php';
        self::assertFileExists($auditPath);
        require_once $auditPath;

        $result = InventoryAudit::run(self::corpus(), self::translatedAssertionIds());

        self::assertTrue($result['complete'], implode("\n", $result['reasons']));
    }

    public function testAMissingTranslatedAssertionMakesTheInventoryIncomplete(): void
    {
        $auditPath = dirname(__DIR__, 2) . '/tools/Test262/InventoryAudit.php';
        self::assertFileExists($auditPath);
        require_once $auditPath;

        $corpus = self::corpus();
        foreach ($corpus['fixtures'] as &$fixture) {
            if ($fixture['path'] === 'test/intl402/Locale/constructor-options-script-valid.js') {
                $fixture['detectedAssertions'] = [];
            }
        }
        unset($fixture);

        $result = InventoryAudit::run($corpus, self::translatedAssertionIds());

        self::assertFalse($result['complete']);
        self::assertNotEmpty($result['reasons']);
    }

    public function testEveryAssertionIdentityIncludesItsOriginalExpressionHash(): void
    {
        foreach (self::corpus()['fixtures'] as $fixture) {
            self::assertNotEmpty($fixture['detectedAssertions']);
            foreach ($fixture['detectedAssertions'] as $assertion) {
                self::assertArrayHasKey('sha256', $assertion);
                self::assertIsString($assertion['sha256']);
                self::assertSame(64, strlen($assertion['sha256']));
            }
        }
    }

    public function testTranslatedEvidenceUsesTheCanonicalInventoryIdentities(): void
    {
        $inventoryAssertions = [];
        foreach (self::corpus()['fixtures'] as $fixture) {
            foreach ($fixture['detectedAssertions'] as $assertion) {
                $id = $assertion['id'] ?? null;
                $sha256 = $assertion['sha256'] ?? null;
                self::assertIsString($id);
                self::assertIsString($sha256);
                $inventoryAssertions[$id] = $sha256;
            }
        }

        $contents = file_get_contents(dirname(__DIR__) . '/Test262/evidence.json');
        self::assertNotFalse($contents);
        /** @var array{fixtures: list<array{assertions: list<array{id: string, sha256: string}>}>} $evidence */
        $evidence = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        foreach ($evidence['fixtures'] as $fixture) {
            foreach ($fixture['assertions'] as $assertion) {
                self::assertArrayHasKey($assertion['id'], $inventoryAssertions);
                self::assertSame($inventoryAssertions[$assertion['id']], $assertion['sha256']);
            }
        }
    }

    /**
     * @return array{
     *     fixtures: list<array{path: string, detectedAssertions: list<array<string, mixed>>}>,
     *     fixtureCount: int,
     *     detectedAssertionCount: int
     * }
     */
    private static function corpus(): array
    {
        $contents = file_get_contents(dirname(__DIR__) . '/Test262/corpus.json');
        self::assertNotFalse($contents);

        /** @var array{
         *     fixtures: list<array{path: string, detectedAssertions: list<array<string, mixed>>}>,
         *     fixtureCount: int,
         *     detectedAssertionCount: int
         * } */
        return json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
    }

    /** @return list<string> */
    private static function translatedAssertionIds(): array
    {
        $contents = file_get_contents(dirname(__DIR__) . '/Test262/evidence.json');
        self::assertNotFalse($contents);
        /** @var array{fixtures: list<array{assertions: list<array{id: string}>}>} $evidence */
        $evidence = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

        $ids = [];
        foreach ($evidence['fixtures'] as $fixture) {
            foreach ($fixture['assertions'] as $assertion) {
                $ids[] = $assertion['id'];
            }
        }

        return $ids;
    }
}
