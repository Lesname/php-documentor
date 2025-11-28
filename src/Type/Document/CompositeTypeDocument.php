<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class CompositeTypeDocument extends AbstractNestedTypeDocument
{
    /**
     * @param array<Composite\Property> $properties
     */
    public function __construct(
        public readonly array $properties,
        public readonly bool $allowExtraProperties = false,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }
}
