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
     * @param array<BackedEnum> $cases
     * @param class-string $reference
     * @param string|null $description
     * @param string|null $deprecated
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
