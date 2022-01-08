<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Response\Document;

use LessDocumentor\Route\Response\Document\DynamicRouteResponseDocument;
use LessDocumentor\Route\Response\Document\Property\Code;
use LessDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Response\Document\DynamicRouteResponseDocument
 */
final class DynamicRouteResponseDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $code = new Code(321);
        $output = $this->createMock(TypeDocument::class);

        $doc = new DynamicRouteResponseDocument($code, $output);

        self::assertSame($code, $doc->getCode());
        self::assertSame($output, $doc->getOutput());
    }
}
