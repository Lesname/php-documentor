<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Exception;

use LessDocumentor\Exception\AbstractException;

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
