<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Attribute\DocDeprecated;
use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessHydrator\Attribute\DefaultValue;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

final class MethodInputTypeDocumentor
{
    /**
     * @throws MissingAttribute
     *
     * @psalm-suppress MixedAssignment
     */
    public function document(ReflectionMethod $method): TypeDocument
    {
        $parameters = [];

        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();
            assert($type instanceof ReflectionNamedType, new RuntimeException());

            if (AttributeHelper::hasAttribute($parameter, DefaultValue::class)) {
                $attribute = AttributeHelper::getAttribute($parameter, DefaultValue::class);

                $default = $attribute->default;
                $required = false;
            } else {
                $required = $type->allowsNull() === false && $parameter->isDefaultValueAvailable() === false;
                $default = $parameter->isDefaultValueAvailable()
                    ? $parameter->getDefaultValue()
                    : null;
            }

            $parameters[$parameter->getName()] = new Property(
                $this->getParameterType($parameter),
                $required,
                $default,
                AttributeHelper::hasAttribute($parameter, DocDeprecated::class),
            );
        }

        return new CompositeTypeDocument($parameters);
    }

    private function getParameterType(ReflectionParameter $parameter): TypeDocument
    {
        $type = $parameter->getType();

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

        $paramDocument = (new ObjectInputTypeDocumentor())->document($typename);

        return $type->allowsNull()
            ? $paramDocument->withNullable()
            : $paramDocument;
    }
}
