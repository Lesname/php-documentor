<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\String\Length;
use LessDocumentor\Type\Document\StringTypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\StringTypeDocument
 */
final class StringTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $length = new Length(1, 2);

        $document = new StringTypeDocument(
            $length,
            'format',
            'ref',
            'description',
            'deprecated',
        );

        self::assertSame($length, $document->length);
        self::assertSame('format', $document->format);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
