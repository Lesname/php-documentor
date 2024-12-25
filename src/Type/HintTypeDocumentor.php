<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use ReflectionType;
use RuntimeException;
use ReflectionUnionType;
use ReflectionNamedType;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Type\Document\UnionTypeDocument;

final class HintTypeDocumentor implements TypeDocumentor
{
    private readonly TypeDocumentor $builtinTypeDocumentor;

    public function __construct(
        private readonly TypeDocumentor $classDocumentor,
        ?TypeDocumentor $builtinTypeDocumentor = null,
    ) {
        $this->builtinTypeDocumentor = $builtinTypeDocumentor ?? new BuiltinTypeDocumentor();
    }

    /**
     * @psalm-assert-if-true ReflectionUnionType|ReflectionNamedType $input
     */
    public function canDocument(mixed $input): bool
    {
        return $input instanceof ReflectionUnionType || $input instanceof ReflectionNamedType;
    }

    public function document(mixed $input): TypeDocument
    {
        if (!$input instanceof ReflectionType) {
            throw new UnexpectedInput(ReflectionType::class, $input);
        }

        return match (true) {
            $input instanceof ReflectionUnionType => $this->documentUnion($input),
            $input instanceof ReflectionNamedType => $this->documentNamed($input),
            default => throw new RuntimeException(
                sprintf(
                    "Unsupported reflection type, got '%s'",
                    get_debug_type($input),
                ),
            ),
        };
    }

    /**
     * @throws UnexpectedInput
     */
    private function documentUnion(ReflectionUnionType $union): TypeDocument
    {
        return new UnionTypeDocument(
            array_map(
                fn(ReflectionType $subType): TypeDocument => $this->document($subType),
                $union->getTypes(),
            ),
        );
    }

    /**
     * @throws UnexpectedInput
     */
    protected function documentNamed(ReflectionNamedType $named): TypeDocument
    {
        $typeDocument = $named->isBuiltin()
            ? $this->builtinTypeDocumentor->document($named->getName())
            : $this->classDocumentor->document($named->getName());

        return $named->allowsNull()
            ? $typeDocument->withNullable()
            : $typeDocument;
    }
}
