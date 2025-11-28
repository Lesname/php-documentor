<?php

declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use LesDocumentor\Helper\AttributeHelper;
use LesDocumentor\Type\Attribute\DocMaxDepth;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Document\TypeDocument;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use LesDocumentor\Type\Document\NestedTypeDocument;
use LesDocumentor\Route\Exception\MissingAttribute;

final class ClassParametersTypeDocumentor extends AbstractClassTypeDocumentor
{
    private readonly TypeDocumentor $methodInputTypeDocumentor;

    public function __construct(?TypeDocumentor $methodParameterTypeDocumentor = null)
    {
        $this->methodInputTypeDocumentor = $methodParameterTypeDocumentor ?? new MethodParametersTypeDocumentor(new HintTypeDocumentor($this));
    }

    /**
     * @param class-string $class
     *
     * @throws MissingAttribute
     * @throws UnexpectedInput
     * @throws ReflectionException
     */
    #[Override]
    protected function documentClass(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $constructor = $classReflected->getConstructor();
        assert($constructor instanceof ReflectionMethod);

        $typeDocument = $this
            ->methodInputTypeDocumentor
            ->document($constructor)
            ->withReference($class);

        if ($typeDocument instanceof NestedTypeDocument && AttributeHelper::hasAttribute($classReflected, DocMaxDepth::class)) {
            $attribute = AttributeHelper::getAttribute($classReflected, DocMaxDepth::class);

            return $typeDocument->withMaxDepth($attribute->maxDepth);
        }

        return $typeDocument;
    }
}
