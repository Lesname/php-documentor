<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use RuntimeException;
use LessValueObject\String\AbstractStringValueObject;

/**
 * @psalm-immutable
 */
final class Path extends AbstractStringValueObject
{
    private readonly string $resource;

    private readonly string $action;

    public function __construct(string $string)
    {
        parent::__construct($string);

        $parts = explode('/', $string);
        $lastPart = $parts[count($parts) - 1];

        $parts = explode('.', $lastPart);

        if (count($parts) < 2) {
            throw new RuntimeException("$string");
        }

        $lastKey = count($parts) - 1;
        $action = $parts[$lastKey];
        unset($parts[$lastKey]);

        $this->action = $action;
        $this->resource = implode('.', $parts);
    }

    /**
     * @psalm-pure
     */
    public static function getMinimumLength(): int
    {
        return 1;
    }

    /**
     * @psalm-pure
     */
    public static function getMaximumLength(): int
    {
        return 255;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
