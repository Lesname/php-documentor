<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\CompositeTypeDocument
 */
final class CompositeTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $fiz = $this->createMock(TypeDocument::class);
        $property = new Property($fiz);

        $document = new CompositeTypeDocument(
            ['fiz' => $property],
            true,
            'ref',
            'description',
        );

        self::assertSame(['fiz' => $property], $document->properties);
        self::assertSame(true, $document->allowExtraProperties);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
    }
}
