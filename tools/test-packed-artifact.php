<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\PackageSmoke;

require dirname(__DIR__) . '/vendor/autoload.php';

$archive = $argv[1] ?? null;
if (!is_string($archive) || !is_file($archive)) {
    throw new InvalidArgumentException('Pass the exact Composer archive to test.');
}
if (!class_exists(ZipArchive::class)) {
    throw new RuntimeException('The zip extension is required to inspect the packed artifact.');
}

$extractionDirectory = PackageSmoke::temporaryDirectory('intl-locale-artifact');
try {
    $zip = new ZipArchive();
    if ($zip->open($archive) !== true || !$zip->extractTo($extractionDirectory)) {
        throw new RuntimeException(sprintf('Unable to extract packed artifact %s.', $archive));
    }
    $zip->close();

    $packageDirectory = $extractionDirectory;
    if (!is_file($packageDirectory . DIRECTORY_SEPARATOR . 'composer.json')) {
        $candidates = glob($extractionDirectory . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . 'composer.json');
        if ($candidates === false || count($candidates) !== 1) {
            throw new RuntimeException('The packed artifact must contain one package root.');
        }
        $packageDirectory = dirname($candidates[0]);
    }
    if (is_dir($packageDirectory . DIRECTORY_SEPARATOR . 'vendor')) {
        throw new RuntimeException('The packed artifact must not contain development dependencies.');
    }

    PackageSmoke::installFromDirectory($packageDirectory);
} finally {
    PackageSmoke::removeDirectory($extractionDirectory);
}
