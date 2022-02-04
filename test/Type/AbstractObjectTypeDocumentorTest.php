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
use LessValueObject\Number\Int\Unsigned;
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
            public static function getMinLength(): int
            {
                return 1;
            }

            public static function getMaxLength(): int
            {
                return 5;
            }
        };

        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
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

        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
        $document = $documentor->document($valueObject::class);

        self::assertInstanceOf(NumberTypeDocument::class, $document);

        self::assertSame(1, $document->range->minimal);
        self::assertSame(5.43, $document->range->maximal);
        self::assertEquals(new Unsigned(3), $document->precision);
        self::assertTrue($document->isRequired());
        self::assertSame($valueObject::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());
    }

    public function testEnumValueObject(): void
    {
        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
        $document = $documentor->document(EnumStub::class);

        self::assertInstanceOf(EnumTypeDocument::class, $document);

        self::assertSame(EnumStub::cases(), $document->cases);
        self::assertTrue($document->isRequired());
        self::assertSame(EnumStub::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());
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

            public static function getItemType(): string
            {
                return EnumStub::class;
            }
        };

        $documentor = $this->getMockForAbstractClass(AbstractObjectTypeDocumentor::class);
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

        self::assertSame(EnumStub::cases(), $item->cases);
        self::assertTrue($item->isRequired());
        self::assertSame(EnumStub::class, $item->getReference());
    }
}
