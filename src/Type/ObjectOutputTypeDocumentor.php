<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
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
     * @throws ReflectionException
     */
    protected function documentObject(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $properties = [];

        foreach ($classReflected->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $type = $property->getType();

            assert($type instanceof ReflectionNamedType, new RuntimeException());

            if ($type->isBuiltin()) {
                if ($type->getName() === 'bool') {
                    $properties[$property->getName()] = $type->allowsNull()
                        ? (new BoolTypeDocument())->withNullable()
                        : new BoolTypeDocument();

                    continue;
                }

                if ($type->getName() === 'array') {
                    $comp = new CompositeTypeDocument([], true);

                    $properties[$property->getName()] = $type->allowsNull()
                        ? $comp->withNullable()
                        : $comp;

                    continue;
                }

                throw new RuntimeException();
            }

            $typeClass = $type->getName();
            assert(class_exists($typeClass), new RuntimeException());

            $propDocument = $this->document($typeClass);
            $properties[$property->getName()] = $type->allowsNull()
                ? $propDocument->withNullable()
                : $propDocument;
        }

        return new CompositeTypeDocument($properties, reference: $class);
    }
}
