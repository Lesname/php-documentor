<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Attribute;

use LessDocumentor\Route\Attribute\DocHttpProxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Attribute\DocHttpProxy
 */
final class DocHttpProxyTest extends TestCase
{
    public function testSetup(): void
    {
        $attr = new DocHttpProxy('fiz', 'biz');

        self::assertSame('fiz', $attr->class);
        self::assertSame('biz', $attr->method);
    }
}
