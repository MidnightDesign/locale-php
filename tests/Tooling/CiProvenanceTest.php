<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\Provenance;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Provenance::class)]
final class CiProvenanceTest extends TestCase
{
    public function testItRecordsTheEvidenceNeededToReproduceARuntimeLane(): void
    {
        $root = dirname(__DIR__, 2);
        $intlLoaded = extension_loaded('intl');
        $extensionMode = $intlLoaded ? 'native' : 'absent';
        $evidence = Provenance::collect(
            root: $root,
            requestedPhp: '8.2',
            runnerLabel: 'ubuntu-24.04',
            extensionMode: $extensionMode,
            branchTrace: [
                'mode' => $extensionMode,
                'intlLoaded' => $intlLoaded,
                'eligibleNativePaths' => [],
                'exercisedNativePaths' => [],
                'fallbackReason' => 'no-native-path-implemented',
            ],
        );

        self::assertSame(1, $evidence['format']);
        self::assertSame('8.2', $evidence['runtime']['requestedPhp']);
        self::assertSame(PHP_VERSION, $evidence['runtime']['actualPhp']);
        self::assertSame('ubuntu-24.04', $evidence['runner']['label']);
        self::assertSame(PHP_OS_FAMILY, $evidence['runner']['osFamily']);
        self::assertSame(php_uname('m'), $evidence['runner']['architecture']);
        self::assertSame(PHP_INT_SIZE, $evidence['runtime']['integerSize']);
        self::assertSame(PHP_ZTS === 1, $evidence['runtime']['threadSafe']);
        self::assertContains('Core', $evidence['runtime']['extensions']);
        self::assertSame($extensionMode, $evidence['extension']['mode']);
        self::assertSame($intlLoaded, $evidence['extension']['intlLoaded']);
        if ($intlLoaded) {
            self::assertNotNull($evidence['extension']['icuVersion']);
        } else {
            self::assertNull($evidence['extension']['icuVersion']);
        }
        self::assertSame(hash_file('sha256', $root.'/composer.lock'), $evidence['dependencies']['lockSha256']);
        self::assertMatchesRegularExpression('/^[a-f0-9]{40}$/', $evidence['actions']['checkout']);
        self::assertSame(
            'b1c961988b9a07894b1dc3dc2b5626ea48387d61',
            $evidence['conformanceBaseline']['ecma402'],
        );
        self::assertSame(
            '419d3e0a2273ba01a3bfcbec423f2801425b8e93',
            $evidence['conformanceBaseline']['test262'],
        );
        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $evidence['releaseDataSnapshot']['fingerprint']);
        self::assertSame('no-native-path-implemented', $evidence['extension']['branchTrace']['fallbackReason']);
    }
}
