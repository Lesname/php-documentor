<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Document\Property;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Document\Property\Method;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Document\Property\Method::class)]
final class MethodTest extends TestCase
{
    public function testPost(): void
    {
        self::assertSame('post', Method::Post->value);
    }
}
