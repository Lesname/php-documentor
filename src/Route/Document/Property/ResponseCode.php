<?php

declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property;

use LesDocumentor\Route\Document\Property\Exception\InvalidResponseCode;

/**
 * @psalm-immutable
 */
final class ResponseCode
{
    /**
     * @throws InvalidResponseCode
     */
    public function __construct(public readonly int $value)
    {
        if ($value < 100 || $value >= 600) {
            throw new InvalidResponseCode($value);
        }
    }

    public function isInformational(): bool
    {
        return $this->value >= 100 && $this->value <= 199;
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
