<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\Collection\Size;
use LessDocumentor\Type\Document\CollectionTypeDocument;
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
        $size = new Size(1, 2);

        $document = new CollectionTypeDocument(
            $item,
            $size,
            'ref',
            'description',
        );

        self::assertSame($item, $document->item);
        self::assertSame($size, $document->size);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
    }
}
