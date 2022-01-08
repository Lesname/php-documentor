<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
interface TypeDocument
{
    public function isRequired(): bool;

    public function getReference(): ?string;

    public function getDescription(): ?string;

    public function getDeprecated(): ?string;
}
