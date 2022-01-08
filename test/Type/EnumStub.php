<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessValueObject\Enum\AbstractEnumValueObject;

final class EnumStub extends AbstractEnumValueObject
{
    public static function cases(): array
    {
        return [
            'foo',
            'fiz',
        ];
    }
}