<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use A;
use BackedEnum;
use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Attribute\DocStringFormat;
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
use ReflectionClass;
use ReflectionException;

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
     *
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentStringValueObject(string $class): TypeDocument
    {
        $refClass = new ReflectionClass($class);

        $format = AttributeHelper::hasAttribute($refClass, DocStringFormat::class)
            ? AttributeHelper::getAttribute($refClass, DocStringFormat::class)->name
            : null;

        return new StringTypeDocument(new Length($class::getMinLength(), $class::getMaxLength()), $format, $class);
    }

    /**
     * @param class-string $class
     */
    abstract protected function documentObject(string $class): TypeDocument;
}
