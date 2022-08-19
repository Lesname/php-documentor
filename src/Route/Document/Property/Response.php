<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessDocumentor\Type\Document\TypeDocument;

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
