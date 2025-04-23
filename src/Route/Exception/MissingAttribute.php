<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class MissingAttribute extends AbstractException
{
    /**
     * @param string $reflecting
     * @param string $attribute
     */
    public function __construct(public readonly string $reflecting, public readonly string $attribute)
    {
        parent::__construct("{$reflecting} requires attribute {$attribute}");
    }
}
