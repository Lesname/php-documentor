<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Property;

use LessValueObject\Composite\AbstractCompositeValueObject;

/**
 * @psalm-immutable
 */
final class Length extends AbstractCompositeValueObject
{
    public function __construct(
        public readonly int $minimal,
        public readonly int $maximal,
    ) {}
}
