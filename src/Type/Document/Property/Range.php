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
        public readonly float|int $minimal,
        public readonly float|int $maximal,
    ) {}
}
