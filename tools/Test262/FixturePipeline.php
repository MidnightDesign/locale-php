<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

interface FixturePipeline
{
    public function run(string $source, string $fixturePath): FixtureResult;
}
