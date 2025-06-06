<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

use Override;

/**
 * @psalm-immutable
 */
abstract class AbstractNestedTypeDocument extends AbstractTypeDocument implements NestedTypeDocument
{
    protected ?int $maxDepth;

    public function __construct(?string $reference = null, ?string $description = null, bool $nullable = false)
    {
        parent::__construct($reference, $description, $nullable);

        $this->maxDepth = null;
    }

    #[Override]
    public function getMaxDepth(): ?int
    {
        return $this->maxDepth;
    }

    #[Override]
    public function withMaxDepth(int $maxDepth): static
    {
        $clone = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $clone->maxDepth = $maxDepth;

        return $clone;
    }
}
