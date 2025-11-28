<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class ReflectionTypeNotSupported extends AbstractException
{
    public function __construct(public readonly string $type)
    {
        parent::__construct("Reflection type {$type} is not supported");
    }
}
