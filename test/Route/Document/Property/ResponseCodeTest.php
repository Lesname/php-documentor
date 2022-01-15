<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Document\Property;

use LessDocumentor\Route\Document\Property\ResponseCode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Document\Property\ResponseCode
 */
final class ResponseCodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $value = 200;

        self::assertTrue((new ResponseCode($value))->isSuccess());

        self::assertFalse((new ResponseCode($value))->isRedirection());
        self::assertFalse((new ResponseCode($value))->isError());
        self::assertFalse((new ResponseCode($value))->isErrorClient());
        self::assertFalse((new ResponseCode($value))->isErrorServer());
    }

    public function testRedirect(): void
    {
        $value = 300;

        self::assertTrue((new ResponseCode($value))->isRedirection());

        self::assertFalse((new ResponseCode($value))->isSuccess());
        self::assertFalse((new ResponseCode($value))->isError());
        self::assertFalse((new ResponseCode($value))->isErrorClient());
        self::assertFalse((new ResponseCode($value))->isErrorServer());
    }

    public function testErrorClient(): void
    {
        $value = 400;

        self::assertTrue((new ResponseCode($value))->isError());
        self::assertTrue((new ResponseCode($value))->isErrorClient());

        self::assertFalse((new ResponseCode($value))->isSuccess());
        self::assertFalse((new ResponseCode($value))->isRedirection());
        self::assertFalse((new ResponseCode($value))->isErrorServer());
    }

    public function testErrorServer(): void
    {
        $value = 500;

        self::assertTrue((new ResponseCode($value))->isError());
        self::assertTrue((new ResponseCode($value))->isErrorServer());

        self::assertFalse((new ResponseCode($value))->isSuccess());
        self::assertFalse((new ResponseCode($value))->isRedirection());
        self::assertFalse((new ResponseCode($value))->isErrorClient());
    }
}
