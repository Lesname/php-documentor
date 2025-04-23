<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Attribute;

use LesDocumentor\Route\Attribute\DocInputProvided;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Route\Attribute\DocInputProvided
 */
final class DocInputProvidedTest extends TestCase
{
    public function testSetup(): void
    {
        $attr = new DocInputProvided(['fiz']);

        self::assertSame(['fiz'], $attr->keys);
    }
}
