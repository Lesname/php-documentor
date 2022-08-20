<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use RuntimeException;

/**
 * @psalm-immutable
 */
final class ResponseCode
{
    public function __construct(public readonly int $code)
    {
        if ($this->code < 200 || $this->code >= 600) {
            throw new RuntimeException();
        }
    }

    public function isSuccess(): bool
    {
        return $this->code >= 200 && $this->code <= 299;
    }

    public function isRedirection(): bool
    {
        return $this->code >= 300 && $this->code <= 399;
    }

    public function isError(): bool
    {
        return $this->code >= 400 && $this->code <= 599;
    }

    public function isErrorClient(): bool
    {
        return $this->code >= 400 && $this->code <= 499;
    }

    public function isErrorServer(): bool
    {
        return $this->code >= 500 && $this->code <= 599;
    }
}
