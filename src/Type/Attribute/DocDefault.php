<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Attribute;

use Attribute;

/**
 * Mark a property as deprecated
 *
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class DocDefault
{
    public function __construct(public readonly mixed $default)
    {}
}
