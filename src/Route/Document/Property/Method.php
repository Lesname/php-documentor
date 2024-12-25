<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessValueObject\Enum\EnumValueObject;
use LessValueObject\Enum\Helper\EnumValueHelper;

/**
 * @psalm-immutable
 */
enum Method: string implements EnumValueObject
{
    use EnumValueHelper;

    case Connect = 'connect';
    case Delete = 'delete';
    case Get = 'get';
    case Head = 'head';
    case Options = 'options';
    case Patch = 'patch';
    case Post = 'post';
    case Put = 'put';
    case Trace = 'trace';
}
