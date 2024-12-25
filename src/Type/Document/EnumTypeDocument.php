<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class EnumTypeDocument extends AbstractTypeDocument
{
    /**
     * @param array<string> $cases
     */
    public function __construct(
        public readonly array $cases,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }
}
