<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class UnexpectedInput extends AbstractException
{
    public function __construct(public readonly string $expected, public readonly mixed $gotten)
    {
        parent::__construct(
            sprintf(
                "Expected '%s', gotten '%s'",
                $expected,
                get_debug_type($this->gotten),
            )
        );
    }
}
