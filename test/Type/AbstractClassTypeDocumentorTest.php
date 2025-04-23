<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type;

use RuntimeException;
use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\AbstractClassTypeDocumentor;
use LesDocumentor\Type\Document\CollectionTypeDocument;
use LesDocumentor\Type\Document\EnumTypeDocument;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\StringTypeDocument;
use LesValueObject\Number\Float\AbstractFloatValueObject;
use LesValueObject\Collection\AbstractCollectionValueObject;
use LesValueObject\String\AbstractStringValueObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractClassTypeDocumentor::class)]
final class AbstractClassTypeDocumentorTest extends TestCase
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


        $documentor = new class extends AbstractClassTypeDocumentor {
            protected function documentClass(string $class): TypeDocument
            {
                throw new RuntimeException();
            }
        };

        $document = $documentor->document($valueObject::class);

        self::assertInstanceOf(StringTypeDocument::class, $document);

        self::assertSame(1, $document->length->minimal);
        self::assertSame(5, $document->length->maximal);
        self::assertSame($valueObject::class, $document->getReference());
        self::assertNull($document->getDescription());
    }

    public function testNumberValueObject(): void
    {
        $valueObject = new class (3.213) extends AbstractFloatValueObject {
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


        $documentor = new class extends AbstractClassTypeDocumentor {
            protected function documentClass(string $class): TypeDocument
            {
                throw new RuntimeException();
            }
        };

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

        $documentor = new class extends AbstractClassTypeDocumentor {
            protected function documentClass(string $class): TypeDocument
            {
                throw new RuntimeException();
            }
        };

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

        $documentor = new class extends AbstractClassTypeDocumentor {
            protected function documentClass(string $class): TypeDocument
            {
                throw new RuntimeException();
            }
        };

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
