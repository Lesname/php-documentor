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
     * @param bool $required
     * @param string|null $reference
     * @param string|null $description
     * @param string|null $deprecated
     */
    public function __construct(
        public array $cases,
        bool $required,
        ?string $reference,
        ?string $description,
        ?string $deprecated,
    ) {
        parent::__construct($required, $reference, $description, $deprecated);
    }
}
