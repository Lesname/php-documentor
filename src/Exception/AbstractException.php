<?php

declare(strict_types=1);

namespace LesDocumentor\Exception;

use Exception;

/**
 * @psalm-immutable
 *
 * @psalm-suppress MutableDependency
 */
abstract class AbstractException extends Exception implements DocumentorException
{
}
