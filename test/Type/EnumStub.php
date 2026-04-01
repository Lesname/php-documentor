<?php
// phpcs:ignoreFile enum
declare(strict_types=1);

namespace LesDocumentorTest\Type;

use LesValueObject\Enum\EnumValueObject;
use LesDocumentor\Type\Attribute\DocSkip;

/**
 * @psalm-immutable
 */
enum EnumStub: string implements EnumValueObject
{
    case Foo = 'foo';
    case Fiz = 'fiz';
    #[DocSkip]
    case Baz = 'baz';

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
