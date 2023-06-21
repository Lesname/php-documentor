<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use A;
use BackedEnum;
use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Type\Document\String\Pattern;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Attribute\DocFormat;
use LessDocumentor\Type\Attribute\DocStringFormat;
use LessDocumentor\Type\Document\Collection\Size;
use LessDocumentor\Type\Document\CompositeTypeDocument;
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
use LessValueObject\Composite\DynamicCompositeValueObject;
use LessValueObject\String\Format\FormattedStringValueObject;
use LessValueObject\String\Format\AbstractRegularExpressionStringValueObject;

abstract class AbstractObjectTypeDocumentor
{
    /**
     * @param class-string $class
     *
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    public function document(string $class): TypeDocument
    {
        if ($class === DynamicCompositeValueObject::class) {
            return new CompositeTypeDocument([], true);
        }

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
            new Size(
                $class::getMinimumSize(),
                $class::getMaximumSize(),
            ),
            $class,
        );
    }

    /**
     * @param class-string<BackedEnum> $class
     */
    protected function documentEnum(string $class): TypeDocument
    {
        return new EnumTypeDocument(
            array_map(
                static fn (BackedEnum $enum): string => (string)$enum->value,
                $class::cases(),
            ),
            $class,
        );
    }

    /**
     * @param class-string<NumberValueObject> $class
     *
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentNumberValueObject(string $class): TypeDocument
    {
        $refClass = new ReflectionClass($class);

        $format = AttributeHelper::hasAttribute($refClass, DocFormat::class)
            ? AttributeHelper::getAttribute($refClass, DocFormat::class)->name
            : null;

        return new NumberTypeDocument(
            new Range(
                $class::getMinimumValue(),
                $class::getMaximumValue(),
            ),
            $class::getMultipleOf(),
            $class::getPrecision(),
            $format,
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

        if (AttributeHelper::hasAttribute($refClass, DocFormat::class)) {
            $format = AttributeHelper::getAttribute($refClass, DocFormat::class)->name;
        } elseif (AttributeHelper::hasAttribute($refClass, DocStringFormat::class)) {
            $format = AttributeHelper::getAttribute($refClass, DocStringFormat::class)->name;
        } else {
            $format = null;
        }

        $pattern = is_subclass_of($class, AbstractRegularExpressionStringValueObject::class)
            ? new Pattern($class::getRegularExpression())
            : null;

        return new StringTypeDocument(
            new Length($class::getMinimumLength(), $class::getMaximumLength()),
            $format,
            $pattern,
            $class,
        );
    }

    /**
     * @param class-string $class
     */
    abstract protected function documentObject(string $class): TypeDocument;
}
