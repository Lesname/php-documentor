<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite;

use LesDocumentor\Type\Document\TypeDocument;

final class Discriminator
{
    /**
     * @param array<string, TypeDocument> $mapping
     */
    public function __construct(
        public readonly string $field,
        public readonly string $property,
        public readonly array $mapping,
    ) {}
}
