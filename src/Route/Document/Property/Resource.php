<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessValueObject\String\AbstractStringValueObject;

/**
 * @psalm-immutable
 */
final class Resource extends AbstractStringValueObject
{
    /**
     * @psalm-pure
     */
    public static function getMinimumLength(): int
    {
        return 1;
    }

    /**
     * @psalm-pure
     */
    public static function getMaximumLength(): int
    {
        return 40;
    }
}
