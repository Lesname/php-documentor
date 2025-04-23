<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property;

use LesDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
final class Response
{
    public function __construct(
        public readonly ResponseCode $code,
        public readonly ?TypeDocument $output,
    ) {}
}
