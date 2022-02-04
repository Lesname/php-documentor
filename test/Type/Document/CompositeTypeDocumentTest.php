<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

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

        $document = new CompositeTypeDocument(
            ['fiz' => $fiz],
            'ref',
            true,
            'description',
            'deprecated',
        );

        self::assertSame(['fiz' => $fiz], $document->properties);
        self::assertTrue($document->isRequired());
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
