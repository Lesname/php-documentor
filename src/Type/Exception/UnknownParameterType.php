<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class UnknownParameterType extends AbstractException
{
    public function __construct(public readonly string $name)
    {
        parent::__construct("Parameter {$name} has no type info");
    }
}
