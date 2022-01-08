<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\Property\Length;
use LessDocumentor\Type\Document\Property\Range;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Collection\CollectionValueObject;
use LessValueObject\Enum\EnumValueObject;
use LessValueObject\Number\Exception\MaxOutBounds;
use LessValueObject\Number\Exception\MinOutBounds;
use LessValueObject\Number\Exception\PrecisionOutBounds;
use LessValueObject\Number\Int\PositiveInt;
use LessValueObject\Number\NumberValueObject;
use LessValueObject\String\StringValueObject;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

final class ObjectOutputTypeDocumentor
{
    /**
     * @param class-string $class
     *
     * @throws ReflectionException
     * @throws PrecisionOutBounds
     * @throws MaxOutBounds
     * @throws MinOutBounds
     */
    public function document(string $class): TypeDocument
    {
        return match (true) {
            is_subclass_of($class, StringValueObject::class) => $this->documentStringValueObject($class),
            is_subclass_of($class, NumberValueObject::class) => $this->documentNumberValueObject($class),
            is_subclass_of($class, EnumValueObject::class) => $this->documentEnumValueObject($class),
            is_subclass_of($class, CollectionValueObject::class) => $this->documentCollectionValueObject($class),
            default => $this->documentObject($class),
        };
    }

    /**
     * @param class-string<CollectionValueObject> $class
     *
     * @throws ReflectionException
     * @throws PrecisionOutBounds
     * @throws MaxOutBounds
     * @throws MinOutBounds
     */
    private function documentCollectionValueObject(string $class): TypeDocument
    {
        return new CollectionTypeDocument(
            $this->document($class::getItem()),
            new Length($class::getMinlength(), $class::getMaxLength()),
            $class,
        );
    }

    /**
     * @param class-string<EnumValueObject> $class
     */
    private function documentEnumValueObject(string $class): TypeDocument
    {
        return new EnumTypeDocument($class::cases(), $class);
    }

    /**
     * @param class-string<NumberValueObject> $class
     *
     * @throws PrecisionOutBounds
     * @throws MaxOutBounds
     * @throws MinOutBounds
     */
    private function documentNumberValueObject(string $class): TypeDocument
    {
        return new NumberTypeDocument(new Range($class::getMinValue(), $class::getMaxValue()), new PositiveInt($class::getPrecision()), $class);
    }

    /**
     * @param class-string<StringValueObject> $class
     */
    private function documentStringValueObject(string $class): TypeDocument
    {
        return new StringTypeDocument(new Length($class::getMinLength(), $class::getMaxLength()), $class);
    }

    /**
     * @param class-string $class
     *
     * @psalm-suppress RedundantCondition needed for phpstan
     *
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     * @throws MaxOutBounds
     * @throws MinOutBounds
     */
    private function documentObject(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $properties = [];

        foreach ($classReflected->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $type = $property->getType();

            assert($type instanceof ReflectionNamedType, new RuntimeException());
            assert($type->isBuiltin() === false, new RuntimeException());

            $class = $type->getName();
            assert(class_exists($class), new RuntimeException());

            $propDocument = $this->document($class);
            $properties[$property->getName()] = $type->allowsNull()
                ? $propDocument->withRequired(false)
                : $propDocument;
        }

        return new CompositeTypeDocument($properties, $class);
    }
}
