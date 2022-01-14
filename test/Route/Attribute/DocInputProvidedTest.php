<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Attribute;

use LessDocumentor\Route\Attribute\DocInputProvided;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Attribute\DocInputProvided
 */
final class DocInputProvidedTest extends TestCase
{
    public function testSetup(): void
    {
        $attr = new DocInputProvided(['fiz']);

        self::assertSame(['fiz'], $attr->keys);
    }
}
