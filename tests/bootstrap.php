<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

date_default_timezone_set('UTC');
if (setlocale(LC_ALL, 'C') === false) {
    throw new RuntimeException('Unable to set the process locale to C.');
}
if (extension_loaded('intl')) {
    Locale::setDefault('en_US_POSIX');
}

$extensionMode = getenv('INTL_LOCALE_EXTENSION_MODE');
if ($extensionMode !== false && $extensionMode !== '') {
    $trace = Midnight\Intl\Tools\Ci\ExtensionMode::trace(
        $extensionMode,
        extension_loaded('intl'),
        [],
        [],
    );
    $tracePath = getenv('INTL_LOCALE_BRANCH_TRACE');
    if ($tracePath === false || $tracePath === '') {
        throw new RuntimeException('INTL_LOCALE_BRANCH_TRACE is required when an extension mode is declared.');
    }

    $traceDirectory = dirname($tracePath);
    if (!is_dir($traceDirectory) && !mkdir($traceDirectory, 0777, true) && !is_dir($traceDirectory)) {
        throw new RuntimeException(sprintf('Unable to create trace directory %s.', $traceDirectory));
    }
    file_put_contents($tracePath, json_encode($trace, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)."\n");
}
