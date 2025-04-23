<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Document\Property;

use LesDocumentor\Route\Document\Property\Method;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Route\Document\Property\Method
 */
final class MethodTest extends TestCase
{
    public function testPost(): void
    {
        self::assertSame('post', Method::Post->value);
    }
}
