<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Number\Exception\MaxOutBounds;
use LessValueObject\Number\Exception\MinOutBounds;
use LessValueObject\Number\Exception\PrecisionOutBounds;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;

final class MethodInputTypeDocumentor
{
    /**
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     *
     * @psalm-suppress RedundantCondition Needed for phpstan
     */
    public function document(ReflectionMethod $method): TypeDocument
    {
        $parameters = [];
        $required = [];

        $objInputDocumentor = new ObjectInputTypeDocumentor();

        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();

            assert($type instanceof ReflectionNamedType, new RuntimeException());

            if ($type->allowsNull() === false && $parameter->isDefaultValueAvailable() === false) {
                $required[] = $parameter->getName();
            }

            if ($type->isBuiltin()) {
                if ($type->getName() === 'bool') {
                    $parameters[$parameter->getName()] = $type->allowsNull()
                        ? (new BoolTypeDocument())->withNullable()
                        : new BoolTypeDocument();

                    continue;
                }

                throw new RuntimeException();
            }

            $typeClass = $type->getName();
            assert(class_exists($typeClass));

            $paramDocument = $objInputDocumentor->document($typeClass);
            $parameters[$parameter->getName()] = $type->allowsNull()
                ? $paramDocument->withNullable()
                : $paramDocument;
        }

        return new CompositeTypeDocument($parameters, $required);
    }
}
