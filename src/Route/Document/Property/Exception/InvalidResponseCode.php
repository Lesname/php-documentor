<?php

declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class InvalidResponseCode extends AbstractException
{
    public function __construct(public readonly int $responseCode)
    {
        parent::__construct("Invalid response code: {$responseCode}");
    }
}
