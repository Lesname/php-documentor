<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class CollectionTypeDocument extends AbstractTypeDocument
{
    public function __construct(
        public readonly TypeDocument $item,
        public readonly ?Collection\Size $size,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }
}
