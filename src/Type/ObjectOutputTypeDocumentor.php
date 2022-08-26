<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Attribute\DocDefault;
use LessDocumentor\Type\Attribute\DocDeprecated;
use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\Composite\Property;
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
     * @throws ReflectionException
     */
    protected function documentObject(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $properties = [];

        foreach ($classReflected->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $properties[$property->getName()] = new Property(
                $this->getPropertyType($property),
                deprecated: AttributeHelper::hasAttribute($property, DocDeprecated::class),
            );
        }

        return new CompositeTypeDocument($properties, reference: $class);
    }

    private function getPropertyType(ReflectionProperty $property): TypeDocument
    {
        $type = $property->getType();
        assert($type instanceof ReflectionNamedType, new RuntimeException());

        $typename = $type->getName();

        if (!class_exists($typename)) {
            return match ($typename) {
                'bool' => $type->allowsNull()
                    ? (new BoolTypeDocument())->withNullable()
                    : new BoolTypeDocument(),
                'array' => $type->allowsNull()
                    ? (new CompositeTypeDocument([], true))->withNullable()
                    : new CompositeTypeDocument([], true),
                default => throw new RuntimeException($typename),
            };
        }

        return $type->allowsNull()
            ? $this->document($typename)->withNullable()
            : $this->document($typename);
    }
}
