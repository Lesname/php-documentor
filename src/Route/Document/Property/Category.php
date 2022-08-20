<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use RuntimeException;

/**
 * @psalm-immutable
 */
enum Category: string
{
    case Command = 'command';
    case Query = 'query';

    /**
     * @param array<mixed> $tags
     */
    public static function fromTags(array $tags): Category
    {
        foreach (self::cases() as $case) {
            if (in_array($case->value, $tags)) {
                return $case;
            }
        }

        throw new RuntimeException();
    }
}
