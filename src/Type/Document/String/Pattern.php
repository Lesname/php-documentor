<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document\String;

use Override;
use LesValueObject\String\AbstractStringValueObject;

/**
 * @psalm-immutable
 */
final class Pattern extends AbstractStringValueObject
{
    /**
     * @psalm-pure
     */
    #[Override]
    public static function getMinimumLength(): int
    {
        return 3;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public static function getMaximumLength(): int
    {
        return 255;
    }
}
