<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessValueObject\Enum\AbstractEnumValueObject;

/**
 * @psalm-immutable
 */
final class Method extends AbstractEnumValueObject
{
    private const POST = 'post';

    /**
     * @psalm-pure
     */
    public static function post(): self
    {
        return self::from(self::POST);
    }

    /**
     * @psalm-pure
     */
    public static function cases(): array
    {
        return [
            self::POST,
        ];
    }
}
