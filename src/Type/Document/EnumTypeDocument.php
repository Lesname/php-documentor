<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

use BackedEnum;

/**
 * @psalm-immutable
 */
final class EnumTypeDocument extends AbstractTypeDocument
{
    /**
     * @param array<BackedEnum|string> $cases
     */
    public function __construct(
        public readonly array $cases,
        ?string $reference = null,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($reference, $description, $deprecated);
    }
}
