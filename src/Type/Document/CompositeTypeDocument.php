<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document;

use LesDocumentor\Type\Document\Composite\Discriminator;

/**
 * @psalm-immutable
 */
final class CompositeTypeDocument extends AbstractNestedTypeDocument
{
    private(set) ?Discriminator $discriminator;

    /**
     * @param array<Composite\Property> $properties
     */
    public function __construct(
        public readonly array $properties,
        public readonly bool $allowExtraProperties = false,
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);

        $this->discriminator = null;
    }

    public function withDiscriminator(?Discriminator $discriminator): self
    {
        $new = clone $this;
        // @phpstan-ignore property.readOnlyByPhpDocAssignNotInConstructor
        $new->discriminator = $discriminator;

        return $new;
    }
}
