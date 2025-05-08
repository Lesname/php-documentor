<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite\Key;

/**
 * @psalm-immutable
 */
interface Key
{
    public function matches(string $value): bool;
}
