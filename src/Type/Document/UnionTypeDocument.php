<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

use Override;

/**
 * @psalm-immutable
 */
final class UnionTypeDocument extends AbstractTypeDocument
{
    /** @var array<TypeDocument> */
    public array $subTypes;

    /**
     * @param array<TypeDocument> $subTypes
     */
    public function __construct(
        array $subTypes,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);

        $normalizedSubTypes = [];

        foreach ($subTypes as $subType) {
            if ($subType instanceof UnionTypeDocument) {
                $normalizedSubTypes = [...$normalizedSubTypes, ...$subType->subTypes];
            } else {
                $normalizedSubTypes[] = $subType;
            }
        }

        $this->subTypes = $normalizedSubTypes;
    }

    /**
     * @deprecated use containsNull
     */
    #[Override]
    public function isNullable(): bool
    {
        if (array_any($this->subTypes, fn($subType) => $subType->isNullable())) {
            return true;
        }

        return parent::isNullable();
    }

    public function containsNull(): bool
    {
        return array_any($this->subTypes, fn($subType) => $subType instanceof NullTypeDocument);
    }

    public static function nullable(TypeDocument $document, TypeDocument ...$orDocument): self
    {
        return new self(
            [
                new NullTypeDocument(),
                $document,
                ...$orDocument,
            ],
        );
    }
}
