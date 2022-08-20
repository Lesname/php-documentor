<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Attribute;

use Attribute;

/**
 * Mark a property as deprecated
 *
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final class DocDeprecated
{
}
