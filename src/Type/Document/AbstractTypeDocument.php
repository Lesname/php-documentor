<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
abstract class AbstractTypeDocument implements TypeDocument
{
    private bool $nullable = false;

    /**
     * @param class-string|null $reference
     * @param string|null $description
     * @param string|null $deprecated
     */
    public function __construct(
        private readonly ?string $reference = null,
        private ?string $description = null,
        private ?string $deprecated = null,
    ) {}

    /**
     * @return class-string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function withNullable(): TypeDocument
    {
        $clone = clone $this;
        $clone->nullable = true;

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
