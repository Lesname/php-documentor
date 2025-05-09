<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Attribute\DocInputProvided;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Attribute\DocInputProvided::class)]
final class DocInputProvidedTest extends TestCase
{
    public function testSetup(): void
    {
        $attr = new DocInputProvided(['fiz']);

        self::assertSame(['fiz'], $attr->keys);
    }
}
