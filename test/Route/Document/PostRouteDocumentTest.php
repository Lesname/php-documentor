<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Document;

use LessDocumentor\Route\Document\PostRouteDocument;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Document\PostRouteDocument
 */
final class PostRouteDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $deprecated = new Deprecated('fiz', null);
        $input = $this->createMock(TypeDocument::class);

        $response = new Response(new ResponseCode(204), null);

        $doc = new PostRouteDocument(
            'path',
            'resource',
            $deprecated,
            ['fiz' => $input],
            [$response],
        );

        self::assertSame(Method::Post, $doc->getMethod());
        self::assertSame('path', $doc->getPath());
        self::assertSame('resource', $doc->getResource());
        self::assertSame($deprecated, $doc->getDeprecated());
        self::assertSame(['fiz' => $input], $doc->getInput());
        self::assertSame([$response], $doc->getRespones());
    }
}
