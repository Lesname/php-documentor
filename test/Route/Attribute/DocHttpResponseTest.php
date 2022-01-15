<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Attribute;

use LessDocumentor\Route\Attribute\DocHttpResponse;
use LessValueObject\ValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Attribute\DocHttpResponse
 */
final class DocHttpResponseTest extends TestCase
{
    public function testWithOutput(): void
    {
        $attr = new DocHttpResponse(ValueObject::class);

        self::assertSame(ValueObject::class, $attr->output);
        self::assertSame(200, $attr->code);
    }

    public function testWithoutOutput(): void
    {
        $attr = new DocHttpResponse();

        self::assertNull($attr->output);
        self::assertSame(204, $attr->code);
    }
}
