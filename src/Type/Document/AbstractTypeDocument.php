<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
abstract class AbstractTypeDocument implements TypeDocument
{
    /**
     * @param class-string|null $reference
     * @param bool $required
     * @param string|null $description
     * @param string|null $deprecated
     */
    public function __construct(
        private readonly ?string $reference,
        private bool $required = true,
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

    public function withRequired(bool $required): TypeDocument
    {
        $clone = clone $this;
        $clone->required = $required;

        return $clone;
    }

    public function isRequired(): bool
    {
        return $this->required;
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
