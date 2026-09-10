<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\Provenance;

require dirname(__DIR__).'/vendor/autoload.php';

if ($argc !== 5) {
    fwrite(STDERR, "Usage: php tools/record-ci-provenance.php <output> <requested-php> <runner-label> <extension-mode>\n");
    exit(2);
}

[, $output, $requestedPhp, $runnerLabel, $extensionMode] = $argv;
$tracePath = getenv('INTL_LOCALE_BRANCH_TRACE');
if ($tracePath === false || !is_file($tracePath)) {
    throw new RuntimeException('The branch trace does not exist. Run the test suite with CI bootstrap variables first.');
}
$traceJson = file_get_contents($tracePath);
if ($traceJson === false) {
    throw new RuntimeException(sprintf('Unable to read branch trace %s.', $tracePath));
}
$trace = json_decode($traceJson, true, flags: JSON_THROW_ON_ERROR);
if (!is_array($trace)
    || !is_string($trace['mode'] ?? null)
    || !is_bool($trace['intlLoaded'] ?? null)
    || !is_array($trace['eligibleNativePaths'] ?? null)
    || !is_array($trace['exercisedNativePaths'] ?? null)
    || (!is_string($trace['fallbackReason'] ?? null) && ($trace['fallbackReason'] ?? null) !== null)) {
    throw new RuntimeException('The branch trace has an invalid shape.');
}

date_default_timezone_set('UTC');
setlocale(LC_ALL, 'C');
if (extension_loaded('intl')) {
    Locale::setDefault('en_US_POSIX');
}

/** @var array{mode: string, intlLoaded: bool, eligibleNativePaths: list<string>, exercisedNativePaths: list<string>, fallbackReason: string|null} $trace */
$evidence = Provenance::collect(dirname(__DIR__), $requestedPhp, $runnerLabel, $extensionMode, $trace);
$directory = dirname($output);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Unable to create evidence directory %s.', $directory));
}
file_put_contents($output, json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)."\n");
