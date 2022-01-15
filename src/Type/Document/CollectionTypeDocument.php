<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class CollectionTypeDocument extends AbstractTypeDocument
{
    /**
     * @param class-string $reference
     */
    public function __construct(
        public TypeDocument $item,
        public Property\Length $length,
        ?string $reference,
        bool $required = true,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($reference, $required, $description, $deprecated);
    }
}
