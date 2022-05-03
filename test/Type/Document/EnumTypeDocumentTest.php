<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentorTest\Type\EnumStub;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\EnumTypeDocument
 */
final class EnumTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $document = new EnumTypeDocument(
            EnumStub::cases(),
            'ref',
            'description',
            'deprecated',
        );

        self::assertSame(EnumStub::cases(), $document->cases);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
