<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

use LessValueObject\Number\Int\PositiveInt;

/**
 * @psalm-immutable
 */
final class NumberTypeDocument extends AbstractTypeDocument
{
    public function __construct(
        public Property\Range $range,
        public PositiveInt $precision,
        bool $required,
        ?string $reference = null,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($required, $reference, $description, $deprecated);
    }
}
