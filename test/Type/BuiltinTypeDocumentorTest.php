<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessDocumentor\Type\BuiltinTypeDocumentor;
use PHPUnit\Framework\TestCase;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\AnyTypeDocument;
use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\NullTypeDocument;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\CollectionTypeDocument;

/**
 * @covers \LessDocumentor\Type\BuiltinTypeDocumentor
 */
class BuiltinTypeDocumentorTest extends TestCase
{
    /**
     * @dataProvider getTestValues
     *
     * @throws UnexpectedInput
     */
    public function testDocument(string $input, TypeDocument $expected): void
    {
        $documentor = new BuiltinTypeDocumentor();
        $result = $documentor->document($input);

        self::assertEquals($expected, $result);
    }

    /**
     * @return array<string, TypeDocument>
     */
    public static function getTestValues(): array
    {
        return [
            ['null', new NullTypeDocument()],
            ['bool', new BoolTypeDocument()],
            ['int', new NumberTypeDocument(new Range(PHP_INT_MIN, PHP_INT_MAX), 1)],
            ['float', new NumberTypeDocument(new Range(PHP_FLOAT_MIN, PHP_FLOAT_MAX), null)],
            ['string', new StringTypeDocument(null)],
            ['array', new CollectionTypeDocument(new AnyTypeDocument(), null)],
            ['object', new CompositeTypeDocument([], true)],
            ['mixed', new AnyTypeDocument()],
            ['iterable', new CollectionTypeDocument(new AnyTypeDocument(), null)],
        ];
    }
}
