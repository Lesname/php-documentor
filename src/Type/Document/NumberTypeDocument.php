<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class NumberTypeDocument extends AbstractTypeDocument
{
    public function __construct(
        public readonly ?Number\Range $range,
        public readonly float|int|null $multipleOf,
        public readonly ?string $format = null,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }
}
