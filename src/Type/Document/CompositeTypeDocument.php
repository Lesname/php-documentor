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
     * @param class-string $reference
     * @param bool $required
     * @param string|null $description
     * @param string|null $deprecated
     */
    public function __construct(
        iterable $properties,
        ?string $reference,
        bool $required = true,
        ?string $description = null,
        ?string $deprecated = null,
    ) {
        parent::__construct($reference, $required, $description, $deprecated);

        foreach ($properties as $name => $property) {
            $this->properties[$name] = $property;
        }
    }
}
