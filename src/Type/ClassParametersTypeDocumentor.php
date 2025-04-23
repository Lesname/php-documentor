<?php
declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Document\TypeDocument;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

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
     * @throws UnexpectedInput
     * @throws ReflectionException
     */
    #[Override]
    protected function documentClass(string $class): TypeDocument
    {
        $classReflected = new ReflectionClass($class);
        $constructor = $classReflected->getConstructor();
        assert($constructor instanceof ReflectionMethod);

        return $this
            ->methodInputTypeDocumentor
            ->document($constructor)
            ->withReference($class);
    }
}
