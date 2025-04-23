<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Attribute;

use LesDocumentor\Route\Attribute\DocHttpProxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Route\Attribute\DocHttpProxy
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
