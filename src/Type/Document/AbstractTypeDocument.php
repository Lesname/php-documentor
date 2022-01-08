<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
abstract class AbstractTypeDocument implements TypeDocument
{
    public function __construct(
        private bool $required,
        private ?string $reference = null,
        private ?string $description = null,
        private ?string $deprecated = null,
    ) {}

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDeprecated(): ?string
    {
        return $this->deprecated;
    }
}
