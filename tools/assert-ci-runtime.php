<?php

declare(strict_types=1);

if ($argc < 3 || $argc > 4) {
    fwrite(STDERR, "Usage: php tools/assert-ci-runtime.php <integer-size> <thread-safe:true|false> [icu-version]\n");
    exit(2);
}

$integerSize = filter_var($argv[1], FILTER_VALIDATE_INT);
$threadSafe = filter_var($argv[2], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
if ($integerSize === false || $threadSafe === null) {
    throw new InvalidArgumentException('The integer-size or thread-safe expectation is invalid.');
}
if (PHP_INT_SIZE !== $integerSize) {
    throw new RuntimeException(sprintf('Expected %d-byte integers; got %d.', $integerSize, PHP_INT_SIZE));
}
if ((PHP_ZTS === 1) !== $threadSafe) {
    throw new RuntimeException(sprintf('Expected threadSafe=%s; got %s.', $threadSafe ? 'true' : 'false', PHP_ZTS === 1 ? 'true' : 'false'));
}

$expectedIcu = $argv[3] ?? null;
if ($expectedIcu !== null) {
    if (!extension_loaded('intl') || !defined('INTL_ICU_VERSION')) {
        throw new RuntimeException('The ICU lane did not load ext-intl.');
    }
    if ($expectedIcu !== 'latest-provisioned' && INTL_ICU_VERSION !== $expectedIcu) {
        throw new RuntimeException(sprintf('Expected ICU %s; got %s.', $expectedIcu, INTL_ICU_VERSION));
    }
}
