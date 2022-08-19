<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\Property\Range;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\NumberTypeDocument
 */
final class NumberTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $range = new Range(1, 2);

        $document = new NumberTypeDocument(
            $range,
            3,
            'ref',
            'description',
            'deprecated',
        );

        self::assertSame($range, $document->range);
        self::assertSame(3, $document->precision);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
