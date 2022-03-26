<?php
// phpcs:ignoreFile enum not supported yet
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

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
}
