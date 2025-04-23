<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document;

use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Document\NumberTypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\NumberTypeDocument
 */
final class NumberTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $range = new Range(1, 2);

        $document = new NumberTypeDocument(
            $range,
            .001,
            'format',
            'ref',
            'description',
        );

        self::assertSame($range, $document->range);
        self::assertSame(.001, $document->multipleOf);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
    }
}
