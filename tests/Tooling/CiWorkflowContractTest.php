<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\WorkflowContract;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WorkflowContract::class)]
final class CiWorkflowContractTest extends TestCase
{
    public function testPreparedWorkflowsPreserveTheCiPolicyWithoutActivatingIt(): void
    {
        self::assertSame([], WorkflowContract::validate(dirname(__DIR__, 2)));
    }
}
