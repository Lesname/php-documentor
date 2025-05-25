<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class CannotHandleRoute extends AbstractException
{
    /**
     * @param array<mixed> $route
     */
    public function __construct(public readonly array $route)
    {
        parent::__construct('Cannot handle route');
    }
}
