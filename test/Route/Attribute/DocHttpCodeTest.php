<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Attribute;

use LessDocumentor\Route\Attribute\DocHttpCode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Attribute\DocHttpCode
 */
final class DocHttpCodeTest extends TestCase
{
    public function testSetup(): void
    {
        $attr = new DocHttpCode(205);

        self::assertSame(205, $attr->code);
    }
}
