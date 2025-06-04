<?php
declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use ReflectionType;
use ReflectionUnionType;
use ReflectionNamedType;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Document\NullTypeDocument;
use LesDocumentor\Type\Document\UnionTypeDocument;
use LesDocumentor\Type\Exception\ReflectionTypeNotSupported;

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
    #[Override]
    public function canDocument(mixed $input): bool
    {
        return $input instanceof ReflectionUnionType || $input instanceof ReflectionNamedType;
    }

    /**
     * @throws ReflectionTypeNotSupported
     * @throws UnexpectedInput
     */
    #[Override]
    public function document(mixed $input): TypeDocument
    {
        if (!$input instanceof ReflectionType) {
            throw new UnexpectedInput(ReflectionType::class, $input);
        }

        return match (true) {
            $input instanceof ReflectionUnionType => $this->documentUnion($input),
            $input instanceof ReflectionNamedType => $this->documentNamed($input),
            default => throw new ReflectionTypeNotSupported(get_debug_type($input)),
        };
    }

    /**
     * @throws ReflectionTypeNotSupported
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

        // Added so nested type's with nullable dont trigger an initialize endless
        if ($named->allowsNull()) {
            return UnionTypeDocument::nullable($typeDocument);
        }

        return $typeDocument;
    }
}
