<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Response\Document\Property;

use LessDocumentor\Route\Response\Document\Property\Code;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Response\Document\Property\Code
 */
final class CodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $value = 200;

        self::assertTrue((new Code($value))->isSuccess());

        self::assertFalse((new Code($value))->isRedirection());
        self::assertFalse((new Code($value))->isError());
        self::assertFalse((new Code($value))->isErrorClient());
        self::assertFalse((new Code($value))->isErrorServer());
    }

    public function testRedirect(): void
    {
        $value = 300;

        self::assertTrue((new Code($value))->isRedirection());

        self::assertFalse((new Code($value))->isSuccess());
        self::assertFalse((new Code($value))->isError());
        self::assertFalse((new Code($value))->isErrorClient());
        self::assertFalse((new Code($value))->isErrorServer());
    }

    public function testErrorClient(): void
    {
        $value = 400;

        self::assertTrue((new Code($value))->isError());
        self::assertTrue((new Code($value))->isErrorClient());

        self::assertFalse((new Code($value))->isSuccess());
        self::assertFalse((new Code($value))->isRedirection());
        self::assertFalse((new Code($value))->isErrorServer());
    }

    public function testErrorServer(): void
    {
        $value = 500;

        self::assertTrue((new Code($value))->isError());
        self::assertTrue((new Code($value))->isErrorServer());

        self::assertFalse((new Code($value))->isSuccess());
        self::assertFalse((new Code($value))->isRedirection());
        self::assertFalse((new Code($value))->isErrorClient());
    }
}
