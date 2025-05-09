<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Document\Property;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Document\Property\Response;
use LesDocumentor\Route\Document\Property\ResponseCode;
use LesDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Document\Property\Response::class)]
final class ResponseTest extends TestCase
{
    public function testSetup(): void
    {
        $code = new ResponseCode(205);

        $ouput = $this->createMock(TypeDocument::class);

        $response = new Response($code, $ouput);

        self::assertSame($code, $response->code);
        self::assertSame($ouput, $response->output);
    }
}
