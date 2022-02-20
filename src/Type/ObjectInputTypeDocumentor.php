<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Number\Exception\MaxOutBounds;
use LessValueObject\Number\Exception\MinOutBounds;
use LessValueObject\Number\Exception\PrecisionOutBounds;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;

final class ObjectInputTypeDocumentor extends AbstractObjectTypeDocumentor
{
    /**
     * @param class-string $class
     *
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     *
     * @psalm-suppress RedundantCondition Needed for phpstan
     */
    protected function documentObject(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $constructor = $classReflected->getConstructor();
        assert($constructor instanceof ReflectionMethod);

        $parameters = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            assert($type instanceof ReflectionNamedType, new RuntimeException());

            if ($type->isBuiltin()) {
                if ($type->getName() === 'bool') {
                    $parameters[$parameter->getName()] = new BoolTypeDocument(null, $parameter->allowsNull() === false);

                    continue;
                }

                throw new RuntimeException();
            }

            $typeClass = $type->getName();
            assert(class_exists($typeClass), new RuntimeException());

            $paramDocument = $this->document($typeClass);
            $parameters[$parameter->getName()] = $type->allowsNull()
                ? $paramDocument->withRequired(false)
                : $paramDocument;
        }

        return new CompositeTypeDocument($parameters, $class);
    }
}
