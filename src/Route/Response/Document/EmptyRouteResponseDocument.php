<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Response\Document;

use LessDocumentor\Route\Response\Document\Property\Code;
use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
final class EmptyRouteResponseDocument implements RouteResponseDocument
{
    public function getCode(): Code
    {
        return new Code(204);
    }

    public function getOutput(): ?TypeDocument
    {
        return null;
    }
}
