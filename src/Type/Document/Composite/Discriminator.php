<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite;

use LesValueObject\ValueObject;

final class Discriminator
{
    /**
     * @param array<string, class-string<ValueObject>> $mapping
     */
    public function __construct(
        public readonly string $field,
        public readonly string $property,
        public readonly array $mapping,
    ) {}
}
