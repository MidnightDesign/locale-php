<?php

declare(strict_types=1);

if ($argc < 6 || $argc > 7) {
    fwrite(
        STDERR,
        "Usage: php tools/assert-ci-runtime.php <integer-size> <thread-safe:true|false> <os-family> <architecture> <php-minor> [icu-version]\n",
    );
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
    throw new RuntimeException(sprintf(
        'Expected threadSafe=%s; got %s.',
        $threadSafe ? 'true' : 'false',
        PHP_ZTS === 1 ? 'true' : 'false',
    ));
}

$expectedOsFamily = $argv[3];
if (PHP_OS_FAMILY !== $expectedOsFamily) {
    throw new RuntimeException(sprintf('Expected OS family %s; got %s.', $expectedOsFamily, PHP_OS_FAMILY));
}

$actualArchitecture = match (strtolower(php_uname('m'))) {
    'amd64', 'x86_64' => 'x64',
    'aarch64', 'arm64' => 'arm64',
    'i386', 'i686', 'x86' => 'x86',
    default => strtolower(php_uname('m')),
};
if ($actualArchitecture !== $argv[4]) {
    throw new RuntimeException(sprintf('Expected architecture %s; got %s.', $argv[4], php_uname('m')));
}

$actualPhp = substr_count($argv[5], '.') === 2 ? PHP_VERSION : PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
if ($actualPhp !== $argv[5]) {
    throw new RuntimeException(sprintf('Expected PHP %s; got %s.', $argv[5], PHP_VERSION));
}

$expectedIcu = ($argv[6] ?? '') !== '' ? $argv[6] : null;
if ($expectedIcu !== null) {
    if (!extension_loaded('intl') || !defined('INTL_ICU_VERSION')) {
        throw new RuntimeException('The ICU lane did not load ext-intl.');
    }
    if ($expectedIcu !== 'latest-provisioned' && INTL_ICU_VERSION !== $expectedIcu) {
        throw new RuntimeException(sprintf('Expected ICU %s; got %s.', $expectedIcu, INTL_ICU_VERSION));
    }
}
