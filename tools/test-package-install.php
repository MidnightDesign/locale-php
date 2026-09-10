<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Ci\PackageSmoke;

require dirname(__DIR__).'/vendor/autoload.php';

PackageSmoke::installFromDirectory($argv[1] ?? dirname(__DIR__));
