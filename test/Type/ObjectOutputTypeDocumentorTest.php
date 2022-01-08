<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\ObjectOutputTypeDocumentor;
use LessValueObject\Collection\AbstractCollectionValueObject;
use LessValueObject\Composite\AbstractCompositeValueObject;
use LessValueObject\Number\AbstractNumberValueObject;
use LessValueObject\Number\Int\Paginate\PerPage;
use LessValueObject\Number\Int\PositiveInt;
use LessValueObject\String\AbstractStringValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\ObjectOutputTypeDocumentor
 */
final class ObjectOutputTypeDocumentorTest extends TestCase
{
    public function testStringValueObject(): void
    {
        $valueObject = new class ('foo') extends AbstractStringValueObject {
            public static function getMinLength(): int
            {
                return 1;
            }

            public static function getMaxLength(): int
            {
                return 5;
            }
        };

        $documentor = new ObjectOutputTypeDocumentor();
        $document = $documentor->document($valueObject::class);

        self::assertInstanceOf(StringTypeDocument::class, $document);

        self::assertSame(1, $document->length->minimal);
        self::assertSame(5, $document->length->maximal);
        self::assertTrue($document->isRequired());
        self::assertSame($valueObject::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());
    }

    public function testNumberValueObject(): void
    {
        $valueObject = new class (3.213) extends AbstractNumberValueObject {
            public static function getPrecision(): int
            {
                return 3;
            }

            public static function getMinValue(): float|int
            {
                return 1;
            }

            public static function getMaxValue(): float|int
            {
                return 5.43;
            }
        };

        $documentor = new ObjectOutputTypeDocumentor();
        $document = $documentor->document($valueObject::class);

        self::assertInstanceOf(NumberTypeDocument::class, $document);

        self::assertSame(1, $document->range->minimal);
        self::assertSame(5.43, $document->range->maximal);
        self::assertEquals(new PositiveInt(3), $document->precision);
        self::assertTrue($document->isRequired());
        self::assertSame($valueObject::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());
    }

    public function testEnumValueObject(): void
    {
        $documentor = new ObjectOutputTypeDocumentor();
        $document = $documentor->document(EnumStub::class);

        self::assertInstanceOf(EnumTypeDocument::class, $document);

        self::assertSame(['foo', 'fiz'], $document->cases);
        self::assertTrue($document->isRequired());
        self::assertSame(EnumStub::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());
    }

    public function testCompositeValueObject(): void
    {
        $perPage = new PerPage(12);
        $stub = EnumStub::from('fiz');

        $composite = new class ($perPage, $stub, 1) extends AbstractCompositeValueObject {
            public function __construct(
                public PerPage $perPage,
                public ?EnumStub $stub,
                private int $foo,
            ) {}
        };

        $documentor = new ObjectOutputTypeDocumentor();
        $document = $documentor->document($composite::class);

        self::assertInstanceOf(CompositeTypeDocument::class, $document);
        self::assertTrue($document->isRequired());
        self::assertSame(EnumStub::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());

        self::assertSame(2, count($document->properties));

        $perPage = $document->properties['perPage'];
        self::assertSame(0, $perPage->range->minimal);
        self::assertSame(100, $perPage->range->maximal);
        self::assertTrue($perPage->isRequired());
        self::assertSame(PerPage::class, $perPage->getReference());
        self::assertNull($perPage->getDescription());
        self::assertNull($perPage->getDeprecated());

        $stub = $document->properties['stub'];
        self::assertSame(['foo', 'fiz'], $stub->cases);
        self::assertFalse($stub->isRequired());
        self::assertSame(EnumStub::class, $stub->getReference());
        self::assertNull($stub->getDescription());
        self::assertNull($stub->getDeprecated());
    }

    public function testCollectionValueObject(): void
    {
        $collection = new class ([]) extends AbstractCollectionValueObject {
            public static function getMinlength(): int
            {
                return 0;
            }

            public static function getMaxLength(): int
            {
                return 5;
            }

            public static function getItem(): string
            {
                return EnumStub::class;
            }
        };

        $documentor = new ObjectOutputTypeDocumentor();
        $document = $documentor->document($collection::class);

        self::assertInstanceOf(CollectionTypeDocument::class, $document);

        self::assertSame(0, $document->length->minimal);
        self::assertSame(5, $document->length->maximal);

        self::assertTrue($document->isRequired());
        self::assertSame($collection::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());

        $item = $document->item;

        self::assertInstanceOf(EnumTypeDocument::class, $item);

        self::assertSame(['foo', 'fiz'], $item->cases);
        self::assertTrue($item->isRequired());
        self::assertSame(EnumStub::class, $item->getReference());
    }
}
