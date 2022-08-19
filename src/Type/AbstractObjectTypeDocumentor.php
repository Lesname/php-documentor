<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use BackedEnum;
use LessDocumentor\Type\Document\Collection\Size;
use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\String\Length;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Collection\CollectionValueObject;
use LessValueObject\Number\NumberValueObject;
use LessValueObject\String\StringValueObject;
use LessValueObject\ValueObject;

abstract class AbstractObjectTypeDocumentor
{
    /**
     * @param class-string $class
     */
    public function document(string $class): TypeDocument
    {
        return match (true) {
            is_subclass_of($class, StringValueObject::class) => $this->documentStringValueObject($class),
            is_subclass_of($class, NumberValueObject::class) => $this->documentNumberValueObject($class),
            is_subclass_of($class, CollectionValueObject::class) => $this->documentCollectionValueObject($class),
            is_subclass_of($class, BackedEnum::class) => $this->documentEnum($class),
            default => $this->documentObject($class),
        };
    }

    /**
     * @param class-string<CollectionValueObject<ValueObject>> $class
     */
    protected function documentCollectionValueObject(string $class): TypeDocument
    {
        return new CollectionTypeDocument(
            $this->document($class::getItemType()),
            new Size($class::getMinlength(), $class::getMaxLength()),
            $class,
        );
    }

    /**
     * @param class-string<BackedEnum> $class
     */
    protected function documentEnum(string $class): TypeDocument
    {
        return new EnumTypeDocument($class::cases(), $class);
    }

    /**
     * @param class-string<NumberValueObject> $class
     */
    protected function documentNumberValueObject(string $class): TypeDocument
    {
        return new NumberTypeDocument(
            new Range($class::getMinValue(), $class::getMaxValue()),
            $class::getPrecision(),
            $class,
        );
    }

    /**
     * @param class-string<StringValueObject> $class
     */
    protected function documentStringValueObject(string $class): TypeDocument
    {
        return new StringTypeDocument(new Length($class::getMinLength(), $class::getMaxLength()), $class);
    }

    /**
     * @param class-string $class
     */
    abstract protected function documentObject(string $class): TypeDocument;
}
