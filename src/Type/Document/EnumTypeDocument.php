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
     * @param class-string $reference
     * @param bool $required
     * @param string|null $description
     * @param string|null $deprecated
     */
    public function __construct(
        public array $cases,
        ?string $reference,
        bool $required = true,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($reference, $required, $description, $deprecated);
    }
}
