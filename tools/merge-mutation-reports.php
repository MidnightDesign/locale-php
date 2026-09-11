<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\MatrixMutationScore;
use Midnight\Intl\Tools\Ci\MutationCampaigns;

require dirname(__DIR__).'/vendor/autoload.php';

if ($argc < 3 || $argc > 4) {
    fwrite(STDERR, "Usage: php tools/merge-mutation-reports.php <output> <reports-directory> [--expect-failing=<campaign>]\n");
    exit(2);
}

$output = $argv[1];
$expectedFailure = null;
if ($argc === 4) {
    $prefix = '--expect-failing=';
    if (!str_starts_with($argv[3], $prefix)) {
        fwrite(STDERR, "The optional argument must use --expect-failing=<campaign>.\n");
        exit(2);
    }
    $expectedFailure = substr($argv[3], strlen($prefix));
    if (!in_array($expectedFailure, MutationCampaigns::names(), true)) {
        fwrite(STDERR, sprintf("Unknown expected-failure campaign %s.\n", $expectedFailure));
        exit(2);
    }
}
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
                throw new RuntimeException(sprintf('Unable to read %s/%s mutation report at %s.', $campaign, $mode, $path));
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
    $accepted = $evidence['passing'];
    if ($expectedFailure !== null) {
        $accepted = MatrixMutationScore::acceptsExpectedFailure($evidence, $expectedFailure);
        $evidence['expectedFailure'] = [
            'campaign' => $expectedFailure,
            'accepted' => $accepted,
        ];
    }
} catch (Throwable $error) {
    $evidence = [
        'format' => 2,
        'passing' => false,
        'error' => $error->getMessage(),
    ];
    $accepted = false;
}

$encoded = json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)."\n";
if (file_put_contents($output, $encoded) === false) {
    throw new RuntimeException(sprintf('Unable to write merged mutation evidence at %s.', $output));
}

if (isset($evidence['error'])) {
    fwrite(STDERR, $evidence['error']."\n");
} elseif ($expectedFailure !== null && !$accepted) {
    $message = $evidence['passing']
        ? sprintf('The %s mutation campaign now passes; remove its temporary expected-failure handling.', $expectedFailure)
        : sprintf('Mutation failures are no longer confined to the expected %s campaign.', $expectedFailure);
    fwrite(STDERR, $message."\n");
}

exit($accepted ? 0 : 1);
