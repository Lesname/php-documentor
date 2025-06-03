<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

use Override;

/**
 * @psalm-immutable
 */
final class UnionTypeDocument extends AbstractTypeDocument
{
    /**
     * @param array<TypeDocument> $subTypes
     */
    public function __construct(
        public array $subTypes,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }

    #[Override]
    public function isNullable(): bool
    {
        if (array_any($this->subTypes, fn($subType) => $subType->isNullable())) {
            return true;
        }

        return parent::isNullable();
    }
}
