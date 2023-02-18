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
        $parts = explode('.', array_pop($parts));

        if (count($parts) < 2) {
            throw new RuntimeException("$string");
        }

        $action = array_pop($parts);

        $this->action = $action;
        $this->resource = implode('.', $parts);
    }

    /**
     * @psalm-pure
     */
    public static function getMinLength(): int
    {
        return 1;
    }

    /**
     * @psalm-pure
     */
    public static function getMaxLength(): int
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
