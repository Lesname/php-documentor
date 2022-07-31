<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class CompositeTypeDocument extends AbstractTypeDocument
{
    /**
     * @param array<string, TypeDocument> $properties
     * @param array<string> $required
     * @param class-string $reference
     * @param string|null $description
     * @param string|null $deprecated
     */
    public function __construct(
        public readonly array $properties,
        public readonly array $required,
        public readonly bool $allowAdditionalProperties = false,
        ?string $reference = null,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($reference, $description, $deprecated);
    }
}
