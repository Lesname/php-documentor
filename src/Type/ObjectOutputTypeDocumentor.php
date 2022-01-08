<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Number\Exception\MaxOutBounds;
use LessValueObject\Number\Exception\MinOutBounds;
use LessValueObject\Number\Exception\PrecisionOutBounds;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

final class ObjectOutputTypeDocumentor extends AbstractObjectTypeDocumentor
{
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
    protected function documentObject(string $class): TypeDocument
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
