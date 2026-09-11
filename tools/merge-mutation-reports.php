<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\MatrixMutationScore;
use Midnight\Intl\Tools\Ci\MutationCampaigns;

require dirname(__DIR__) . '/vendor/autoload.php';

if ($argc < 3 || $argc > 4) {
    fwrite(
        STDERR,
        "Usage: php tools/merge-mutation-reports.php <output> <reports-directory> [--expect-failing=<baseline-file>]\n",
    );
    exit(2);
}

$output = $argv[1];
$expectedFailureBaselinePath = null;
if ($argc === 4) {
    $prefix = '--expect-failing=';
    if (!str_starts_with($argv[3], $prefix)) {
        fwrite(STDERR, "The optional argument must use --expect-failing=<baseline-file>.\n");
        exit(2);
    }
    $expectedFailureBaselinePath = substr($argv[3], strlen($prefix));
}
$directory = dirname($output);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Unable to create evidence directory %s.', $directory));
}

$expectedCampaign = null;
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
    $accepted = $evidence['passing'];
    if ($expectedFailureBaselinePath !== null) {
        $baselineContents = @file_get_contents($expectedFailureBaselinePath);
        if ($baselineContents === false) {
            throw new RuntimeException(sprintf(
                'Unable to read expected mutation failure baseline at %s.',
                $expectedFailureBaselinePath,
            ));
        }
        $baseline = json_decode($baselineContents, true, flags: JSON_THROW_ON_ERROR);
        if (!is_array($baseline)) {
            throw new RuntimeException('The expected mutation failure baseline must contain a JSON object.');
        }
        /** @var array<string, mixed> $baseline */
        $expectedCampaign = $baseline['campaign'] ?? null;
        if (!is_string($expectedCampaign) || !in_array($expectedCampaign, MutationCampaigns::names(), true)) {
            throw new RuntimeException('The expected mutation failure baseline names an unknown campaign.');
        }
        $accepted = MatrixMutationScore::acceptsExpectedFailure($evidence, $baseline);
        $evidence['expectedFailure'] = [
            'campaign' => $expectedCampaign,
            'baseline' => $expectedFailureBaselinePath,
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

$encoded = json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
if (file_put_contents($output, $encoded) === false) {
    throw new RuntimeException(sprintf('Unable to write merged mutation evidence at %s.', $output));
}

if (isset($evidence['error'])) {
    fwrite(STDERR, $evidence['error'] . "\n");
} elseif (is_string($expectedCampaign) && !$accepted) {
    $message = $evidence['passing']
        ? sprintf(
            'The %s mutation campaign now passes; remove its temporary expected-failure handling.',
            $expectedCampaign,
        )
        : sprintf('Mutation evidence regressed beyond the reviewed %s expected-failure baseline.', $expectedCampaign);
    fwrite(STDERR, $message . "\n");
}

exit($accepted ? 0 : 1);
