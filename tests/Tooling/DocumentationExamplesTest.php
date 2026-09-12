<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\DocumentationExamples;
use PHPUnit\Framework\TestCase;

final class DocumentationExamplesTest extends TestCase
{
    public function testPublicDocumentationExamplesRun(): void
    {
        DocumentationExamples::checkPublic(dirname(__DIR__, 2));

        $this->addToAssertionCount(1);
    }
}
