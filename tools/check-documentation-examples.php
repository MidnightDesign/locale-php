<?php

declare(strict_types=1);

use Midnight\Intl\Tools\DocumentationExamples;

require dirname(__DIR__) . '/vendor/autoload.php';

DocumentationExamples::checkPublic(dirname(__DIR__));
