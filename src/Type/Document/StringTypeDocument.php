<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class StringTypeDocument extends AbstractTypeDocument
{
    public function __construct(
        public Property\Length $length,
        bool $required,
        ?string $reference,
        ?string $description,
        ?string $deprecated,
    ) {
        parent::__construct($required, $reference, $description, $deprecated);
    }
}
