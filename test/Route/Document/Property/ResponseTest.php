<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Document\Property;

use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Document\Property\Response
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
