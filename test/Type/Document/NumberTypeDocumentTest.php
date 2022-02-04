<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document;

use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\Property\Range;
use LessValueObject\Number\Int\Unsigned;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\NumberTypeDocument
 */
final class NumberTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $range = new Range(1, 2);
        $precision = new Unsigned(3);

        $document = new NumberTypeDocument(
            $range,
            $precision,
            'ref',
            true,
            'description',
            'deprecated',
        );

        self::assertSame($range, $document->range);
        self::assertSame($precision, $document->precision);
        self::assertTrue($document->isRequired());
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
        self::assertSame('deprecated', $document->getDeprecated());
    }
}
