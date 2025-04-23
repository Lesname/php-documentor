<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
interface TypeDocument
{
    public function withReference(string $reference): TypeDocument;

    public function getReference(): ?string;

    public function withNullable(bool $nullable = true): TypeDocument;

    public function isNullable(): bool;

    public function withDescription(string $description): TypeDocument;

    public function getDescription(): ?string;
}
