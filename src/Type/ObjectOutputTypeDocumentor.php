<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Helper\AttributeHelper;
use LessValueObject\String\Exception\TooLong;
use LessValueObject\String\Exception\TooShort;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Attribute\DocDeprecated;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
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
    private readonly TypeDocumentor $builtinTypeDocumentor;

    public function __construct(?TypeDocumentor $builtinTypeDocumentor = null)
    {
        $this->builtinTypeDocumentor = $builtinTypeDocumentor ?? new BuiltinTypeDocumentor();
    }

    /**
     * @param class-string $class
     *
     * @throws ReflectionException
     * @throws TooLong
     * @throws TooShort
     * @throws UnexpectedInput
     * @throws MissingAttribute
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
     * @throws Exception\UnexpectedInput
     * @throws MissingAttribute
     * @throws ReflectionException
     * @throws TooLong
     * @throws TooShort
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

        $typeDocument = $type->isBuiltin()
            ? $this->builtinTypeDocumentor->document($type->getName())
            : $this->document($type->getName());

        return $type->allowsNull()
            ? $typeDocument->withNullable()
            : $typeDocument;
    }
}
