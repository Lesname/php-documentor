<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
interface TypeDocument
{
    /**
     * @param class-string $reference
     */
    public function withReference(string $reference): TypeDocument;

    /**
     * @return class-string|null
     */
    public function getReference(): ?string;

    public function withNullable(): TypeDocument;

    public function isNullable(): bool;

    public function withDescription(string $description): TypeDocument;

    public function getDescription(): ?string;

    public function withDeprecated(string $deprecated): TypeDocument;

    public function getDeprecated(): ?string;
}
