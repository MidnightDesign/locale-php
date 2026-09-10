<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\MatrixMutationScore;

require dirname(__DIR__).'/vendor/autoload.php';

if ($argc !== 8) {
    fwrite(STDERR, "Usage: php tools/merge-mutation-reports.php <output> <spec-absent.json> <spec-disabled.json> <spec-native.json> <porcelain-absent.json> <porcelain-disabled.json> <porcelain-native.json>\n");
    exit(2);
}

$output = $argv[1];
$directory = dirname($output);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Unable to create evidence directory %s.', $directory));
}

try {
    $reports = [];
    foreach (['spec', 'porcelain'] as $campaignIndex => $campaign) {
        foreach (['absent', 'disabled', 'native'] as $modeIndex => $mode) {
            $path = $argv[2 + $campaignIndex * 3 + $modeIndex];
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

    $applicabilityContents = file_get_contents(dirname(__DIR__).'/.ci/mutation-applicability.json');
    if ($applicabilityContents === false) {
        throw new RuntimeException('Unable to read .ci/mutation-applicability.json.');
    }
    $applicabilityDocument = json_decode($applicabilityContents, true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($applicabilityDocument)
        || ($applicabilityDocument['format'] ?? null) !== 1
        || !is_array($applicabilityDocument['mutants'] ?? null)) {
        throw new RuntimeException('.ci/mutation-applicability.json has an invalid shape.');
    }
    /** @var array<string, list<string>> $applicability */
    $applicability = $applicabilityDocument['mutants'];
    $evidence = MatrixMutationScore::aggregate($reports, $applicability);
} catch (Throwable $error) {
    $evidence = [
        'format' => 2,
        'passing' => false,
        'error' => $error->getMessage(),
    ];
}

$encoded = json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)."\n";
if (file_put_contents($output, $encoded) === false) {
    throw new RuntimeException(sprintf('Unable to write merged mutation evidence at %s.', $output));
}

if (isset($evidence['error'])) {
    fwrite(STDERR, $evidence['error']."\n");
}

exit($evidence['passing'] ? 0 : 1);
