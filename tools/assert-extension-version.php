<?php

declare(strict_types=1);

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php tools/assert-extension-version.php <extension> <version>\n");
    exit(2);
}

$actual = phpversion($argv[1]);
if ($actual !== $argv[2]) {
    throw new RuntimeException(sprintf(
        'Expected %s %s; got %s.',
        $argv[1],
        $argv[2],
        $actual === false ? 'not loaded' : $actual,
    ));
}
