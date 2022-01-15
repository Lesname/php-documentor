<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Document\Property;

use LessDocumentor\Route\Document\Property\Method;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Document\Property\Method
 */
final class MethodTest extends TestCase
{
    public function testPost(): void
    {
        self::assertSame('post', Method::post()->value);
    }

    public function testCases(): void
    {
        self::assertSame(
            [
                'post',
            ],
            Method::cases(),
        );
    }
}
