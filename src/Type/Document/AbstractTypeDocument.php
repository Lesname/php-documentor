<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

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

    public function withReference(string $reference): TypeDocument
    {
        $clone = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $clone->reference = $reference;

        return $clone;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function withNullable(bool $nullable = true): TypeDocument
    {
        $clone = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $clone->nullable = $nullable;

        return $clone;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function withDescription(string $description): TypeDocument
    {
        $clone = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $clone->description = $description;

        return $clone;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
