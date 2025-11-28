<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class StringTypeDocument extends AbstractTypeDocument
{
    public function __construct(
        public readonly ?String\Length $length,
        public readonly ?string $format = null,
        public readonly ?String\Pattern $pattern = null,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }
}
