<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\TypeDocument;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

final class ObjectInputTypeDocumentor extends AbstractObjectTypeDocumentor
{
    /**
     * @param class-string $class
     *
     * @throws ReflectionException
     */
    protected function documentObject(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $constructor = $classReflected->getConstructor();
        assert($constructor instanceof ReflectionMethod);

        return (new MethodInputTypeDocumentor())
            ->document($constructor)
            ->withReference($class);
    }
}
