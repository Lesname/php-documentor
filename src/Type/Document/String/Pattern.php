<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\String;

use LessValueObject\String\AbstractStringValueObject;

/**
 * @psalm-immutable
 */
final class Pattern extends AbstractStringValueObject
{
    /**
     * @psalm-pure
     */
    public static function getMinLength(): int
    {
        return 3;
    }

    /**
     * @psalm-pure
     */
    public static function getMaxLength(): int
    {
        return 255;
    }
}
