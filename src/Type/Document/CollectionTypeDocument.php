<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class CollectionTypeDocument extends AbstractNestedTypeDocument
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
