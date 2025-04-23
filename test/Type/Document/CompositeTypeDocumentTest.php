<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document;

use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\CompositeTypeDocument
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
