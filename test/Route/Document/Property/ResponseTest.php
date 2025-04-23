<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Document\Property;

use LesDocumentor\Route\Document\Property\Response;
use LesDocumentor\Route\Document\Property\ResponseCode;
use LesDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Route\Document\Property\Response
 */
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
