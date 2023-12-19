<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessDocumentor\Type\AbstractObjectTypeDocumentor;
use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessValueObject\Collection\AbstractCollectionValueObject;
use LessValueObject\Number\AbstractNumberValueObject;
use LessValueObject\String\AbstractStringValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\AbstractObjectTypeDocumentor
 */
final class AbstractObjectTypeDocumentorTest extends TestCase
{
    public function testStringValueObject(): void
    {
        $valueObject = new class ('foo') extends AbstractStringValueObject {
            public static function getMinimumLength(): int
            {
                return 1;
            }

            public static function getMaximumLength(): int
            {
                return 5;
            }
        };

        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
        $document = $documentor->document($valueObject::class);

        self::assertInstanceOf(StringTypeDocument::class, $document);

        self::assertSame(1, $document->length->minimal);
        self::assertSame(5, $document->length->maximal);
        self::assertSame($valueObject::class, $document->getReference());
        self::assertNull($document->getDescription());
    }

    public function testNumberValueObject(): void
    {
        $valueObject = new class (3.213) extends AbstractNumberValueObject {
            public static function getMultipleOf(): float|int
            {
                return .001;
            }

            public static function getMinimumValue(): float|int
            {
                return 1;
            }

            public static function getMaximumValue(): float|int
            {
                return 5.43;
            }
        };

        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
        $document = $documentor->document($valueObject::class);

        self::assertInstanceOf(NumberTypeDocument::class, $document);

        self::assertSame(1, $document->range->minimal);
        self::assertSame(5.43, $document->range->maximal);
        self::assertEquals(.001, $document->multipleOf);
        self::assertSame($valueObject::class, $document->getReference());
        self::assertNull($document->getDescription());
    }

    public function testEnumValueObject(): void
    {
        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
        $document = $documentor->document(EnumStub::class);

        self::assertInstanceOf(EnumTypeDocument::class, $document);

        self::assertSame(['foo', 'fiz'], $document->cases);
        self::assertSame(EnumStub::class, $document->getReference());
        self::assertNull($document->getDescription());
    }

    public function testCollectionValueObject(): void
    {
        $collection = new class ([]) extends AbstractCollectionValueObject {
            public static function getMinimumSize(): int
            {
                return 0;
            }

            public static function getMaximumSize(): int
            {
                return 5;
            }

            public static function getItemType(): string
            {
                return EnumStub::class;
            }
        };

        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
        $document = $documentor->document($collection::class);

        self::assertInstanceOf(CollectionTypeDocument::class, $document);

        self::assertSame(0, $document->size->minimal);
        self::assertSame(5, $document->size->maximal);

        self::assertSame($collection::class, $document->getReference());
        self::assertNull($document->getDescription());

        $item = $document->item;

        self::assertInstanceOf(EnumTypeDocument::class, $item);

        self::assertSame(['foo', 'fiz'], $item->cases);
        self::assertSame(EnumStub::class, $item->getReference());
    }
}
