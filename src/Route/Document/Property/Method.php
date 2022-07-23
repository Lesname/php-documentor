<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessValueObject\Enum\EnumValueObject;
use LessValueObject\Enum\Helper\EnumValueHelper;

/**
 * @psalm-immutable
 */
enum Method:string implements EnumValueObject
{
    use EnumValueHelper;

    case Post = 'post';
}
