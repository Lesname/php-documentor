<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

use Override;

/**
 * @psalm-immutable
 */
abstract class AbstractTypeDocument implements TypeDocument
{
    public function __construct(
        protected ?string $reference = null,
        protected ?string $description = null,
        protected bool $nullable = false,
    ) {}

    #[Override]
    public function withReference(string $reference): static
    {
        $clone = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $clone->reference = $reference;

        return $clone;
    }

    #[Override]
    public function getReference(): ?string
    {
        return $this->reference;
    }

    #[Override]
    public function withNullable(bool $nullable = true): static
    {
        $clone = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $clone->nullable = $nullable;

        return $clone;
    }

    #[Override]
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    #[Override]
    public function withDescription(string $description): static
    {
        $clone = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $clone->description = $description;

        return $clone;
    }

    #[Override]
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
