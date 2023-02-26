<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
abstract class AbstractTypeDocument implements TypeDocument
{
    protected bool $nullable = false;

    /**
     * @psalm-suppress ImpureFunctionCall
     */
    public function __construct(
        private ?string $reference = null,
        private ?string $description = null,
        private ?string $deprecated = null,
    ) {
        if (is_string($deprecated)) {
            trigger_error("Parameter 'deprecated' deprecated");
        }
    }

    public function withReference(string $reference): TypeDocument
    {
        $clone = clone $this;
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
        $clone->description = $description;

        return $clone;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function withDeprecated(string $deprecated): TypeDocument
    {
        $clone = clone $this;
        $clone->deprecated = $deprecated;

        return $clone;
    }

    public function getDeprecated(): ?string
    {
        return $this->deprecated;
    }
}
