<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Response\Document;

use LessDocumentor\Route\Response\Document\EmptyRouteResponseDocument;
use LessDocumentor\Route\Response\Document\Property\Code;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Response\Document\EmptyRouteResponseDocument
 */
final class EmptyRouteResponseDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $doc = new EmptyRouteResponseDocument();

        self::assertEquals(new Code(204), $doc->getCode());
        self::assertNull($doc->getOutput());
    }
}
