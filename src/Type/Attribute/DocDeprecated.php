<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Attribute;

use Attribute;

/**
 * Mark something as deprecated
 *
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final class DocDeprecated
{
}
