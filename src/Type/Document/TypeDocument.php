<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
interface TypeDocument
{
    public function withReference(string $reference): static;

    public function getReference(): ?string;

    public function withDescription(string $description): static;

    public function getDescription(): ?string;
}
