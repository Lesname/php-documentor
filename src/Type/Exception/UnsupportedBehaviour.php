<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class UnsupportedBehaviour extends AbstractException
{
    public function __construct()
    {
        parent::__construct("Unsupported behaviour");
    }
}
