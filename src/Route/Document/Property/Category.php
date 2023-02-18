<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use RuntimeException;
use LessValueObject\Enum\EnumValueObject;
use LessValueObject\Enum\Helper\EnumValueHelper;

/**
 * @psalm-immutable
 */
enum Category: string implements EnumValueObject
{
    use EnumValueHelper;

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
