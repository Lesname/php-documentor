<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property;

use Override;
use LesValueObject\String\AbstractStringValueObject;

/**
 * @psalm-immutable
 */
final class Resource extends AbstractStringValueObject
{
    /**
     * @psalm-pure
     */
    #[Override]
    public static function getMinimumLength(): int
    {
        return 1;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public static function getMaximumLength(): int
    {
        return 40;
    }
}
