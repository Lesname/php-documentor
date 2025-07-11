<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\String;

/**
 * @psalm-immutable
 */
final class Length
{
    public function __construct(
        public readonly int $minimal,
        public readonly int $maximal,
    ) {}
}
