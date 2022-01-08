<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

use LessValueObject\Number\Int\PositiveInt;
use LessValueObject\ValueObject;

/**
 * @psalm-immutable
 */
final class NumberTypeDocument extends AbstractTypeDocument
{
    /**
     * @param class-string<ValueObject>|null $reference
     */
    public function __construct(
        public Property\Range $range,
        public PositiveInt $precision,
        string $reference,
        bool $required = true,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($reference, $required, $description, $deprecated);
    }
}
