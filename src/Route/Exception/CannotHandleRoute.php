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
    private function __construct(public readonly array $route, int $code)
    {
        parent::__construct('Cannot handle route', $code);
    }

    /**
     * @param array<mixed> $route
     */
    public static function noBaseRoute(array $route): self
    {
        return new self($route, 100);
    }

    /**
     * @param array<mixed> $route
     */
    public static function noSub(array $route): self
    {
        return new self($route, 101);
    }
}
