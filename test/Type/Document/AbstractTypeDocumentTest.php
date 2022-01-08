<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\AbstractTypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\AbstractTypeDocument
 */
final class AbstractTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $document = $this->getMockForAbstractClass(
            AbstractTypeDocument::class,
            [
                true,
                'ref',
                'description',
                'deprecated',
            ],
        );

        self::assertTrue($document->isRequired());
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
