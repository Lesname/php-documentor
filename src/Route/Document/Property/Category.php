<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property;

use RuntimeException;
use LesValueObject\Enum\EnumValueObject;
use LesValueObject\Enum\Helper\EnumValueHelper;

/**
 * @psalm-immutable
 */
enum Category: string implements EnumValueObject
{
    use EnumValueHelper;

    case Command = 'command';
    case Query = 'query';
}
