<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
interface NestedTypeDocument extends TypeDocument
{
    public function getMaxDepth(): ?int;

    public function withMaxDepth(int $maxDepth): static;
}
