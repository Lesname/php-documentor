<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Number;

/**
 * @psalm-immutable
 */
final class Range
{
    public function __construct(
        public readonly float|int $minimal,
        public readonly float|int $maximal,
    ) {}
}
