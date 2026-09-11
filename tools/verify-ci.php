<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\WorkflowContract;

require dirname(__DIR__) . '/vendor/autoload.php';

$failures = WorkflowContract::validate(dirname(__DIR__));
foreach ($failures as $failure) {
    fwrite(STDERR, $failure . "\n");
}

exit($failures === [] ? 0 : 1);
