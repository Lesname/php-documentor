<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\EnumTypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\EnumTypeDocument
 */
final class EnumTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $document = new EnumTypeDocument(
            ['fiz', 'biz'],
            'ref',
            true,
            'description',
            'deprecated',
        );

        self::assertSame(['fiz', 'biz'], $document->cases);
        self::assertTrue($document->isRequired());
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
