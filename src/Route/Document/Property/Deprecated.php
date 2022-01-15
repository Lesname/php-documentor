<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessValueObject\Composite\AbstractCompositeValueObject;

/**
 * @psalm-immutable
 */
final class Deprecated extends AbstractCompositeValueObject
{
    public function __construct(
        public ?string $alternate,
        public ?string $reason,
    ) {
        assert(is_string($alternate) || is_string($this->reason));
    }
}
