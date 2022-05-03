<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\Property\Length;
use LessDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\CollectionTypeDocument
 */
final class CollectionTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $item = $this->createMock(TypeDocument::class);
        $length = new Length(1, 2);

        $document = new CollectionTypeDocument(
            $item,
            $length,
            'ref',
            'description',
            'deprecated',
        );

        self::assertSame($item, $document->item);
        self::assertSame($length, $document->length);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
