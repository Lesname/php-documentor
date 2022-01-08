<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Response\Document;

use LessDocumentor\Route\Response\Document\Property\Code;
use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
final class DynamicRouteResponseDocument implements RouteResponseDocument
{
    public function __construct(private Code $code, private ?TypeDocument $output)
    {}

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getOutput(): ?TypeDocument
    {
        return $this->output;
    }
}
