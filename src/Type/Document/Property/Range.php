<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Property;

use LessValueObject\Composite\AbstractCompositeValueObject;

/**
 * @psalm-immutable
 */
final class Range extends AbstractCompositeValueObject
{
    public function __construct(
        public float|int $minimal,
        public float|int $maximal,
    ) {}
}
