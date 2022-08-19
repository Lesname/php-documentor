<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Number;

/**
 * @psalm-immutable
 */
final class Range
{
    public function __construct(
        public readonly float|int|null $minimal,
        public readonly float|int|null $maximal,
    ) {}
}
