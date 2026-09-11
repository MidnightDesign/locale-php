<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tests\Test262\RunnerTest;
use PHPUnit\Framework\TestCase;

final class Test262RunnerContractTest extends TestCase
{
    public function testGeneratedFixturesHaveDistinctSourceRelativeCliAndJunitIdentities(): void
    {
        $junit = tempnam(sys_get_temp_dir(), 'locale-test262-junit-');
        self::assertIsString($junit);

        try {
            [$exitCode, $output] = self::runPhpUnit([
                '--testsuite',
                'test262-upstream',
                '--no-progress',
                '--testdox',
                '--log-junit',
                $junit,
            ]);

            self::assertSame(0, $exitCode, $output);
            $xml = (string) file_get_contents($junit);
            foreach ([
                'test/intl402/Locale/constructor-unicode-ext-invalid.js',
                'test/intl402/Locale/reject-duplicate-variants.js',
            ] as $fixturePath) {
                self::assertStringContainsString($fixturePath, $output);
                self::assertStringContainsString($fixturePath, $xml);
            }
        } finally {
            @unlink($junit);
        }
    }

    public function testOneGeneratedFixtureCanBeSelectedWithoutExecutingItsSiblings(): void
    {
        $junit = tempnam(sys_get_temp_dir(), 'locale-test262-filter-');
        self::assertIsString($junit);

        try {
            $selected = 'test/intl402/Locale/constructor-unicode-ext-invalid.js';
            [$exitCode, $output] = self::runPhpUnit([
                '--testsuite',
                'test262-upstream',
                '--filter',
                $selected,
                '--no-progress',
                '--log-junit',
                $junit,
            ]);

            self::assertSame(0, $exitCode, $output);
            self::assertStringContainsString('OK (1 test, 6 assertions)', $output);
            $xml = (string) file_get_contents($junit);
            self::assertSame(1, substr_count($xml, '<testcase '));
            self::assertStringContainsString($selected, $xml);
            self::assertStringNotContainsString('reject-duplicate-variants.js', $xml);
        } finally {
            @unlink($junit);
        }
    }

    public function testStaleGeneratedScriptFailsDiscovery(): void
    {
        $path = dirname(__DIR__).'/Test262/Generated/zz-stale.php';
        file_put_contents($path, <<<'PHP'
<?php

// Source: test/intl402/Locale/zz-stale.js at Test262 injected.
PHP);

        try {
            iterator_to_array(RunnerTest::scripts());
            self::fail('Expected stale generated script discovery to fail.');
        } catch (\RuntimeException $error) {
            self::assertStringContainsString('stale: test/intl402/Locale/zz-stale.js', $error->getMessage());
        } finally {
            @unlink($path);
        }
    }

    public function testDuplicateGeneratedSourceIdentityFailsDiscovery(): void
    {
        $generated = dirname(__DIR__).'/Test262/Generated';
        $source = $generated.'/test/intl402/Locale/reject-duplicate-variants.php';
        $duplicate = $generated.'/zz-duplicate.php';
        self::assertTrue(copy($source, $duplicate));

        try {
            iterator_to_array(RunnerTest::scripts());
            self::fail('Expected duplicate generated script discovery to fail.');
        } catch (\RuntimeException $error) {
            self::assertStringContainsString(
                'Multiple generated scripts claim Test262 fixture test/intl402/Locale/reject-duplicate-variants.js',
                $error->getMessage(),
            );
        } finally {
            @unlink($duplicate);
        }
    }

    public function testMalformedGeneratedPhpRemainsAVisibleFailure(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'locale-test262-malformed-');
        self::assertIsString($path);
        file_put_contents($path, '<?php this is not valid PHP');

        try {
            (new RunnerTest('testScript'))->testScript($path);
            self::fail('Expected malformed generated PHP to fail.');
        } catch (\ParseError $error) {
            self::assertNotSame('', $error->getMessage());
        } finally {
            @unlink($path);
        }
    }

    /** @param list<string> $arguments
     *  @return array{int, string}
     */
    private static function runPhpUnit(array $arguments): array
    {
        $root = dirname(__DIR__, 2);
        $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg($root.'/vendor/bin/phpunit');
        foreach ($arguments as $argument) {
            $command .= ' '.escapeshellarg($argument);
        }
        $command .= ' 2>&1';

        exec($command, $lines, $exitCode);

        return [$exitCode, implode("\n", $lines)];
    }
}
