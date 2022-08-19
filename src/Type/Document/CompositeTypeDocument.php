<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class CompositeTypeDocument extends AbstractTypeDocument
{
    /**
     * @param array<string, Composite\Property> $properties
     * @param class-string $reference
     */
    public function __construct(
        public readonly array $properties,
        public readonly bool $allowExtraProperties = false,
        ?string $reference = null,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($reference, $description, $deprecated);
    }
}
