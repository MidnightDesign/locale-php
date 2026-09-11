<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\MatrixMutationScore;
use Midnight\Intl\Tools\Ci\MutationCampaigns;

require dirname(__DIR__) . '/vendor/autoload.php';

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php tools/merge-mutation-reports.php <output> <reports-directory>\n");
    exit(2);
}

$output = $argv[1];
$directory = dirname($output);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Unable to create evidence directory %s.', $directory));
}

try {
    $reports = [];
    $reportsDirectory = rtrim($argv[2], '/\\');
    foreach (MutationCampaigns::names() as $campaign) {
        foreach (MutationCampaigns::extensionModes() as $mode) {
            $path = sprintf('%s/%s/%s.json', $reportsDirectory, $campaign, $mode);
            $contents = @file_get_contents($path);
            if ($contents === false) {
                throw new RuntimeException(sprintf(
                    'Unable to read %s/%s mutation report at %s.',
                    $campaign,
                    $mode,
                    $path,
                ));
            }
            $report = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
            if (!is_array($report) || !is_array($report['stats'] ?? null)) {
                throw new RuntimeException(sprintf('%s/%s mutation report has an invalid shape.', $campaign, $mode));
            }
            /** @var array<string, mixed> $report */
            $reports[$campaign][$mode] = $report;
        }
    }

    $evidence = MatrixMutationScore::aggregate($reports);
} catch (Throwable $error) {
    $evidence = [
        'format' => 2,
        'passing' => false,
        'error' => $error->getMessage(),
    ];
}

$encoded = json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
if (file_put_contents($output, $encoded) === false) {
    throw new RuntimeException(sprintf('Unable to write merged mutation evidence at %s.', $output));
}

if (isset($evidence['error'])) {
    fwrite(STDERR, $evidence['error'] . "\n");
}

exit($evidence['passing'] ? 0 : 1);
