<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class TypeRequired extends AbstractException
{
    public function __construct()
    {
        parent::__construct("Type is required");
    }
}
