<?php
declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use ReflectionClass;
use ReflectionParameter;
use LesDocumentor\Helper\AttributeHelper;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Attribute\DocDeprecated;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use ReflectionMethod;
use LesDocumentor\Type\Exception\UnknownParameterType;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;

final class MethodParametersTypeDocumentor implements TypeDocumentor
{
    private readonly TypeDocumentor $hintTypeDocumentor;

    public function __construct(?TypeDocumentor $hintTypeDocumentor = null)
    {
        $this->hintTypeDocumentor = $hintTypeDocumentor ?? new HintTypeDocumentor(new ClassParametersTypeDocumentor($this));
    }

    #[Override]
    public function canDocument(mixed $input): bool
    {
        return $input instanceof ReflectionMethod;
    }

    /**
     * @throws UnexpectedInput
     */
    #[Override]
    public function document(mixed $input): TypeDocument
    {
        if (!$input instanceof ReflectionMethod) {
            throw new UnexpectedInput(ReflectionMethod::class, $input);
        }

        return (new ReflectionClass(CompositeTypeDocument::class))
            ->newLazyProxy(
                function () use ($input) {
                    $parameters = [];

                    foreach ($input->getParameters() as $parameter) {
                        $parameters[] = $this->documentParameter($parameter);
                    }

                    return new CompositeTypeDocument($parameters);
                },
            );
    }

    /**
     * @throws Exception\ReflectionTypeNotSupported
     * @throws UnexpectedInput
     * @throws UnknownParameterType
     */
    private function documentParameter(ReflectionParameter $parameter): Property
    {
        $type = $parameter->getType();

        if ($type === null) {
            throw new UnknownParameterType($parameter->getName());
        }

        $required = $type->allowsNull() === false && $parameter->isDefaultValueAvailable() === false;
        $paramTypeDocument = $this->hintTypeDocumentor->document($type);

        $default = $parameter->isDefaultValueAvailable()
            ? $parameter->getDefaultValue()
            : null;

        assert(is_scalar($default) || is_object($default) || is_array($default) || $default === null);

        $isDeprecated = AttributeHelper::hasAttribute($parameter, DocDeprecated::class);

        return new Property(
            new ExactKey($parameter->getName()),
            $paramTypeDocument,
            $required,
            $default,
            $isDeprecated,
        );
    }
}
