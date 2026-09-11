<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\Matrix;

require __DIR__ . '/Ci/Matrix.php';

$matrix = Matrix::fromFile(dirname(__DIR__) . '/.ci/matrix.json');
$selection = $argv[1] ?? null;
$lanes = match ($selection) {
    'runtime' => $matrix->runtimeLanes(),
    'runtime-jobs' => $matrix->runtimeJobs(),
    'install' => $matrix->installLanes(($argv[2] ?? '') !== '--without-macos'),
    'arm-runtime' => $matrix->armRuntimeLanes(),
    'windows-x86-runtime' => $matrix->windowsX86RuntimeLanes(),
    'windows-ts-runtime' => $matrix->windowsThreadSafeRuntimeLanes(),
    'icu-runtime' => $matrix->icuRuntimeLanes(),
    'advisory-runtime' => $matrix->advisoryRuntimeLanes(),
    default => throw new InvalidArgumentException('Unknown CI matrix selection.'),
};

echo json_encode(['include' => $lanes], JSON_THROW_ON_ERROR);
