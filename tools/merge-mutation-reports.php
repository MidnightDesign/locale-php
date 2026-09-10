<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\MatrixMutationScore;

require dirname(__DIR__).'/vendor/autoload.php';

if ($argc !== 5) {
    fwrite(STDERR, "Usage: php tools/merge-mutation-reports.php <output> <absent.json> <disabled.json> <native.json>\n");
    exit(2);
}

$output = $argv[1];
$reports = [];
foreach (['absent', 'disabled', 'native'] as $index => $mode) {
    $path = $argv[$index + 2];
    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException(sprintf('Unable to read %s mutation report at %s.', $mode, $path));
    }
    $report = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($report) || !is_array($report['stats'] ?? null)) {
        throw new RuntimeException(sprintf('%s mutation report has an invalid shape.', $mode));
    }
    /** @var array{stats: array<string, int>} $report */
    $reports[$mode] = $report;
}

$evidence = MatrixMutationScore::aggregate($reports);
$directory = dirname($output);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Unable to create evidence directory %s.', $directory));
}
file_put_contents($output, json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)."\n");

exit($evidence['passing'] ? 0 : 1);
