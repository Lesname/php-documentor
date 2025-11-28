<?php

declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property;

use LesValueObject\Enum\EnumValueObject;
use LesValueObject\Enum\Helper\EnumValueHelper;

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
    case Query = 'query';
}
