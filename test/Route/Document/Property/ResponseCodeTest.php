<?php

declare(strict_types=1);

namespace LesDocumentorTest\Route\Document\Property;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Document\Property\ResponseCode;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Document\Property\ResponseCode::class)]
final class ResponseCodeTest extends TestCase
{
    public function testInformational(): void
    {
        $value = 100;

        self::assertTrue((new ResponseCode($value))->isInformational());

        self::assertFalse((new ResponseCode($value))->isSuccess());
        self::assertFalse((new ResponseCode($value))->isRedirection());
        self::assertFalse((new ResponseCode($value))->isError());
        self::assertFalse((new ResponseCode($value))->isErrorClient());
        self::assertFalse((new ResponseCode($value))->isErrorServer());
    }

    public function testSuccess(): void
    {
        $value = 200;

        self::assertTrue((new ResponseCode($value))->isSuccess());

        self::assertFalse((new ResponseCode($value))->isInformational());
        self::assertFalse((new ResponseCode($value))->isRedirection());
        self::assertFalse((new ResponseCode($value))->isError());
        self::assertFalse((new ResponseCode($value))->isErrorClient());
        self::assertFalse((new ResponseCode($value))->isErrorServer());
    }

    public function testRedirect(): void
    {
        $value = 300;

        self::assertTrue((new ResponseCode($value))->isRedirection());

        self::assertFalse((new ResponseCode($value))->isInformational());
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

        self::assertFalse((new ResponseCode($value))->isInformational());
        self::assertFalse((new ResponseCode($value))->isSuccess());
        self::assertFalse((new ResponseCode($value))->isRedirection());
        self::assertFalse((new ResponseCode($value))->isErrorServer());
    }

    public function testErrorServer(): void
    {
        $value = 500;

        self::assertTrue((new ResponseCode($value))->isError());
        self::assertTrue((new ResponseCode($value))->isErrorServer());

        self::assertFalse((new ResponseCode($value))->isInformational());
        self::assertFalse((new ResponseCode($value))->isSuccess());
        self::assertFalse((new ResponseCode($value))->isRedirection());
        self::assertFalse((new ResponseCode($value))->isErrorClient());
    }
}
