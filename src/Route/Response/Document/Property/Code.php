<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Response\Document\Property;

use LessValueObject\Number\Int\AbstractIntValueObject;

/**
 * @psalm-immutable
 */
final class Code extends AbstractIntValueObject
{
    /**
     * @psalm-pure
     */
    public static function getMinValue(): int
    {
        return 200;
    }

    /**
     * @psalm-pure
     */
    public static function getMaxValue(): int
    {
        return 599;
    }

    public function isSuccess(): bool
    {
        return $this->getValue() >= 200 && $this->getValue() <= 299;
    }

    public function isRedirection(): bool
    {
        return $this->getValue() >= 300 && $this->getValue() <= 399;
    }

    public function isError(): bool
    {
        return $this->getValue() >= 400 && $this->getValue() <= 599;
    }

    public function isErrorClient(): bool
    {
        return $this->getValue() >= 400 && $this->getValue() <= 499;
    }

    public function isErrorServer(): bool
    {
        return $this->getValue() >= 500 && $this->getValue() <= 599;
    }
}
