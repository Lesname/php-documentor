<?php

declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class UnprocessableFilename extends AbstractException
{
    public function __construct(public readonly string $filename)
    {
        parent::__construct("Unprocessable filename: {$filename}");
    }
}
