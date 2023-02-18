<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Attribute\DocDeprecated;
use LessDocumentor\Type\Document\AnyTypeDocument;
use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\String\Length;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\UnionTypeDocument;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;
use RuntimeException;

final class ObjectOutputTypeDocumentor extends AbstractObjectTypeDocumentor
{
    /**
     * @param class-string $class
     *
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentObject(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $properties = [];

        foreach ($classReflected->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propType = $property->getType();

            if ($propType === null) {
                throw new RuntimeException();
            }

            $properties[$property->getName()] = new Property(
                $this->getTypeDocument($propType),
                deprecated: AttributeHelper::hasAttribute($property, DocDeprecated::class),
            );
        }

        return new CompositeTypeDocument($properties, reference: $class);
    }

    /**
     * @throws ReflectionException
     * @throws MissingAttribute
     */
    private function getTypeDocument(ReflectionType $type): TypeDocument
    {
        if ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();

            if (count($types) === 1) {
                return $this->getTypeDocument($types[0]);
            }

            return new UnionTypeDocument(
                array_map(
                    function (ReflectionType $type): TypeDocument {
                        return $this->getTypeDocument($type);
                    },
                    $types,
                ),
            );
        }

        assert($type instanceof ReflectionNamedType, new RuntimeException());

        $typename = $type->getName();

        if (!class_exists($typename)) {
            $typeDocument = match ($typename) {
                'array' => new CompositeTypeDocument([], true),
                'bool' => new BoolTypeDocument(),
                'float' => new NumberTypeDocument(null, null, null),
                'int' => new NumberTypeDocument(null, 1, 0),
                'mixed' => new AnyTypeDocument(),
                'string' => new StringTypeDocument(null),
                default => throw new RuntimeException($typename),
            };
        } else {
            $typeDocument = $this->document($typename);
        }

        return $type->allowsNull()
            ? $typeDocument->withNullable()
            : $typeDocument;
    }
}
