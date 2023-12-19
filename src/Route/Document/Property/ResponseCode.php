<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use RuntimeException;

/**
 * @psalm-immutable
 */
final class ResponseCode
{
    public function __construct(public readonly int $value)
    {
        if ($value < 200 || $value >= 600) {
            throw new RuntimeException();
        }
    }

    public function isSuccess(): bool
    {
        return $this->value >= 200 && $this->value <= 299;
    }

    public function isRedirection(): bool
    {
        return $this->value >= 300 && $this->value <= 399;
    }

    public function isError(): bool
    {
        return $this->value >= 400 && $this->value <= 599;
    }

    public function isErrorClient(): bool
    {
        return $this->value >= 400 && $this->value <= 499;
    }

    public function isErrorServer(): bool
    {
        return $this->value >= 500 && $this->value <= 599;
    }
}
