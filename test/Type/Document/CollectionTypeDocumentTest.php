<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document;

use LesDocumentor\Type\Document\Collection\Size;
use LesDocumentor\Type\Document\CollectionTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\CollectionTypeDocument
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
