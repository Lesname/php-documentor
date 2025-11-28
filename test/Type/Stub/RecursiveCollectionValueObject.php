<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Stub;

use LesDocumentor\Type\Attribute\DocMaxDepth;
use LesValueObject\Collection\AbstractCollectionValueObject;

/**
 * @psalm-immutable
 *
 * @extends AbstractCollectionValueObject<RecursiveCollectionValueObject>
 */
#[DocMaxDepth(5)]
final class RecursiveCollectionValueObject extends AbstractCollectionValueObject
{
    /**
     * @psalm-pure
     */
    public static function getMinimumSize(): int
    {
        return 0;
    }

    /**
     * @psalm-pure
     */
    public static function getMaximumSize(): int
    {
        return 9;
    }

    /**
     * @psalm-pure
     */
    public static function getItemType(): string|array
    {
        return RecursiveCollectionValueObject::class;
    }
}
