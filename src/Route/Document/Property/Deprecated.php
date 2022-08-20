<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

/**
 * @psalm-immutable
 */
final class Deprecated
{
    public function __construct(
        public readonly ?string $alternate,
        public readonly ?string $reason,
    ) {
        assert(is_string($alternate) || is_string($this->reason));
    }
}
