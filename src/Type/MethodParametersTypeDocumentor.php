<?php
declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use ReflectionParameter;
use LesDocumentor\Helper\AttributeHelper;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Attribute\DocDeprecated;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use ReflectionMethod;
use RuntimeException;

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

        $parameters = [];

        foreach ($input->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $this->documentParameter($parameter);
        }

        return new CompositeTypeDocument($parameters);
    }

    /**
     * @throws UnexpectedInput
     */
    private function documentParameter(ReflectionParameter $parameter): Property
    {
        $type = $parameter->getType();

        if ($type === null) {
            throw new RuntimeException("Missing type for '{$parameter->getName()}'");
        }

        $required = $type->allowsNull() === false && $parameter->isDefaultValueAvailable() === false;
        $paramTypeDocument = $this->hintTypeDocumentor->document($type);

        $default = $parameter->isDefaultValueAvailable()
            ? $parameter->getDefaultValue()
            : null;

        assert(is_scalar($default) || is_object($default) || is_array($default) || $default === null);

        $isDeprecated = AttributeHelper::hasAttribute($parameter, DocDeprecated::class);

        return new Property($paramTypeDocument, $required, $default, $isDeprecated);
    }
}
