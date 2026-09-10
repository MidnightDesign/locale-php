<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\Matrix;

require dirname(__DIR__).'/vendor/autoload.php';

$matrix = Matrix::fromFile(dirname(__DIR__).'/.ci/matrix.json');
$selection = $argv[1] ?? null;
$lanes = match ($selection) {
    'runtime' => $matrix->runtimeLanes(),
    'install' => $matrix->installLanes(),
    'arm' => $matrix->armLanes(),
    'windows-x86' => $matrix->windowsX86Lanes(),
    'windows-ts' => $matrix->windowsThreadSafeLanes(),
    'icu' => $matrix->icuLanes(),
    'arm-runtime' => $matrix->armRuntimeLanes(),
    'windows-x86-runtime' => $matrix->windowsX86RuntimeLanes(),
    'windows-ts-runtime' => $matrix->windowsThreadSafeRuntimeLanes(),
    'icu-runtime' => $matrix->icuRuntimeLanes(),
    'advisory-runtime' => $matrix->advisoryRuntimeLanes(),
    'specialized-runtime' => $matrix->specializedRuntimeLanes(),
    default => throw new InvalidArgumentException('Unknown CI matrix selection.'),
};

echo json_encode(['include' => $lanes], JSON_THROW_ON_ERROR);
