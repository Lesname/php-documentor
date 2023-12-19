<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class UnionTypeDocument extends AbstractTypeDocument
{
    /**
     * @param array<TypeDocument> $subTypes
     */
    public function __construct(
        public array $subTypes,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }
}
