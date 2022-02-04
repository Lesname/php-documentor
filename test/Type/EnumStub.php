<?php
// phpcs:ignoreFile enum
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessValueObject\Enum\EnumValueObject;

/**
 * @psalm-immutable
 */
enum EnumStub: string implements EnumValueObject
{
    case Foo = 'foo';
    case Fiz = 'Fiz';

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
