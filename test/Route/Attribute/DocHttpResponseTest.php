<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Attribute\DocHttpResponse;
use LesValueObject\ValueObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Attribute\DocHttpResponse::class)]
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
