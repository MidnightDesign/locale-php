<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\MatrixMutationScore;
use Midnight\Intl\Tools\Ci\MutationCampaigns;

require dirname(__DIR__) . '/vendor/autoload.php';

if ($argc !== 4 || !in_array($argv[3], MutationCampaigns::names(), true)) {
    fwrite(STDERR, "Usage: php tools/create-mutation-expected-failure-baseline.php <aggregate> <output> <campaign>\n");
    exit(2);
}

$contents = @file_get_contents($argv[1]);
if ($contents === false) {
    throw new RuntimeException(sprintf('Unable to read aggregate mutation evidence at %s.', $argv[1]));
}
$evidence = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
if (!is_array($evidence) || !is_array($evidence['mutations'] ?? null)) {
    throw new RuntimeException('Aggregate mutation evidence has an invalid shape.');
}
/** @var array{mutations: list<array{id: string, campaign: string, source: string, mutator: string, diff: string, modes: array<string, string>}>} $evidence */
$baseline = MatrixMutationScore::expectedFailureBaseline($evidence, $argv[3]);
$encoded = json_encode($baseline, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
if (file_put_contents($argv[2], $encoded) === false) {
    throw new RuntimeException(sprintf('Unable to write expected mutation failure baseline at %s.', $argv[2]));
}
