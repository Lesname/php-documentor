<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document;

use LesDocumentor\Type\Document\String\Length;
use LesDocumentor\Type\Document\String\Pattern;
use LesDocumentor\Type\Document\StringTypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\StringTypeDocument
 */
final class StringTypeDocumentTest extends TestCase
{
    public function testSetup(): void
    {
        $length = new Length(1, 2);
        $pattern = new Pattern('/f/');

        $document = new StringTypeDocument(
            $length,
            'format',
            $pattern,
            'ref',
            'description',
        );

        self::assertSame($length, $document->length);
        self::assertSame('format', $document->format);
        self::assertSame($pattern, $document->pattern);
        self::assertSame('ref', $document->getReference());
        self::assertSame('description', $document->getDescription());
    }
}
