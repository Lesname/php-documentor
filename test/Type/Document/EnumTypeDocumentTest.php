<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document;

use LesDocumentor\Type\Document\EnumTypeDocument;
use LesDocumentorTest\Type\EnumStub;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\EnumTypeDocument
 */
final class EnumTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $document = new EnumTypeDocument(
            EnumStub::cases(),
            'ref',
            'description',
        );

        self::assertSame(EnumStub::cases(), $document->cases);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
    }
}
