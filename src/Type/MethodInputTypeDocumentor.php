<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

final class MethodInputTypeDocumentor
{
    /**
     * @psalm-suppress RedundantCondition Needed for phpstan
     */
    public function document(ReflectionMethod $method): TypeDocument
    {
        $parameters = [];

        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();

            assert($type instanceof ReflectionNamedType, new RuntimeException());

            $parameters[$parameter->getName()] = new Property(
                $this->getParameterType($parameter),
                $type->allowsNull() === false && $parameter->isDefaultValueAvailable() === false,
                $parameter->isDefaultValueAvailable()
                    ? $parameter->getDefaultValue()
                    : null,
            );
        }

        return new CompositeTypeDocument($parameters);
    }

    private function getParameterType(ReflectionParameter $parameter): TypeDocument
    {
        $type = $parameter->getType();

        assert($type instanceof ReflectionNamedType, new RuntimeException());

        if ($type->isBuiltin()) {
            if ($type->getName() === 'bool') {
                return $type->allowsNull()
                    ? (new BoolTypeDocument())->withNullable()
                    : new BoolTypeDocument();
            }

            if ($type->getName() === 'array') {
                $comp = new CompositeTypeDocument([], true);

                return $type->allowsNull()
                    ? $comp->withNullable()
                    : $comp;
            }
        }

        $typeClass = $type->getName();
        assert(class_exists($typeClass));

        $paramDocument = (new ObjectInputTypeDocumentor())->document($typeClass);

        return $type->allowsNull()
            ? $paramDocument->withNullable()
            : $paramDocument;
    }
}
