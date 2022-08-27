<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use RuntimeException;

final class Path
{
    private readonly string $resource;

    private readonly string $action;

    public function __construct(public readonly string $path)
    {
        $parts = explode('/', $this->path);
        $parts = explode('.', array_pop($parts));

        if (count($parts) < 2) {
            throw new RuntimeException("$path");
        }

        $this->action = array_pop($parts);
        $this->resource = implode('.', $parts);
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
