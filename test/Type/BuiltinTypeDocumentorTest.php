<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type;

use LesDocumentor\Type\BuiltinTypeDocumentor;
use PHPUnit\Framework\TestCase;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use LesDocumentor\Type\Document\AnyTypeDocument;
use LesDocumentor\Type\Document\BoolTypeDocument;
use LesDocumentor\Type\Document\NullTypeDocument;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\StringTypeDocument;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\CollectionTypeDocument;

#[CoversClass(\LesDocumentor\Type\BuiltinTypeDocumentor::class)]
class BuiltinTypeDocumentorTest extends TestCase
{
    /**
     *
     * @throws UnexpectedInput
     */
    #[DataProvider('getTestValues')]
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
