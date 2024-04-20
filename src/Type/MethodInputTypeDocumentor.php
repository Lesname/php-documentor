<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use ReflectionParameter;
use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Type\Attribute\DocDeprecated;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use ReflectionMethod;
use RuntimeException;

final class MethodInputTypeDocumentor implements TypeDocumentor
{
    private readonly TypeDocumentor $hintTypeDocumentor;

    public function __construct(?TypeDocumentor $hintTypeDocumentor = null)
    {
        $this->hintTypeDocumentor = $hintTypeDocumentor ?? new HintTypeDocumentor(new ClassParametersTypeDocumentor($this));
    }

    public function canDocument(mixed $input): bool
    {
        return $input instanceof ReflectionMethod;
    }

    /**
     * @throws UnexpectedInput
     */
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
