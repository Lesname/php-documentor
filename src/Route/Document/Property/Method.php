<?php
// phpcs:ignoreFile enum not supported yet
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessValueObject\Enum\EnumValueObject;

/**
 * @psalm-immutable
 */
enum Method:string implements EnumValueObject
{
    case Post = 'post';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
