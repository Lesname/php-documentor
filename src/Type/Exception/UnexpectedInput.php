<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Exception;

use LessDocumentor\Exception\AbstractException;

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
