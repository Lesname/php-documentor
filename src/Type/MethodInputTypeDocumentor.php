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
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\UnionTypeDocument;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use RuntimeException;

final class MethodInputTypeDocumentor
{
    /**
     * @throws MissingAttribute
     * @throws ReflectionException
     *
     * @psalm-suppress MixedAssignment
     */
    public function document(ReflectionMethod $method): TypeDocument
    {
        $parameters = [];

        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();
            assert($type instanceof ReflectionNamedType, new RuntimeException());

            $required = $type->allowsNull() === false && $parameter->isDefaultValueAvailable() === false;
            $default = $parameter->isDefaultValueAvailable()
                ? $parameter->getDefaultValue()
                : null;

            assert(is_scalar($default) || is_object($default) || is_array($default) || $default === null);

            $propType = $parameter->getType();

            if ($propType === null) {
                throw new RuntimeException();
            }

            $parameters[$parameter->getName()] = new Property(
                $this->getTypeDocument($propType),
                $required,
                $default,
                AttributeHelper::hasAttribute($parameter, DocDeprecated::class),
            );
        }

        return new CompositeTypeDocument($parameters);
    }

    /**
     * @throws MissingAttribute
     * @throws ReflectionException
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
                'float' => new NumberTypeDocument(null, null),
                'int' => new NumberTypeDocument(null, 1),
                'mixed' => new AnyTypeDocument(),
                'string' => new StringTypeDocument(null),
                default => throw new RuntimeException($typename),
            };
        } else {
            $typeDocument = (new ObjectInputTypeDocumentor())->document($typename);
        }

        return $type->allowsNull()
            ? $typeDocument->withNullable()
            : $typeDocument;
    }
}
