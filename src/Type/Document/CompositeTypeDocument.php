<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class CompositeTypeDocument extends AbstractTypeDocument
{
    /** @var array<string, TypeDocument> */
    public array $properties = [];

    /**
     * @param iterable<string, TypeDocument> $properties
     * @param bool $required
     * @param string|null $reference
     * @param string|null $description
     * @param string|null $deprecated
     */
    public function __construct(
        iterable $properties,
        bool $required,
        ?string $reference = null,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($required, $reference, $description, $deprecated);

        foreach ($properties as $name => $property) {
            $this->properties[$name] = $property;
        }
    }
}
