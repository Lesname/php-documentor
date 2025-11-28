<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document\Collection;

/**
 * @psalm-immutable
 */
final class Size
{
    public function __construct(
        public readonly int $minimal,
        public readonly int $maximal,
    ) {}
}
