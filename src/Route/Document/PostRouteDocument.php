<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document;

use LessValueObject\String\Exception\TooLong;
use LessValueObject\String\Exception\TooShort;
use LessDocumentor\Route\Document\Property\Category;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Route\Document\Property\Path;
use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 *
 * @deprecated
 */
final class PostRouteDocument extends RouteDocument
{
    /**
     * @param array<int, Response> $responses
     *
     * @throws TooLong
     * @throws TooShort
     */
    public function __construct(
        Category $category,
        Path $path,
        string $resource,
        ?Deprecated $deprecated,
        TypeDocument $input,
        array $responses,
    ) {
        parent::__construct(
            Method::Post,
            $category,
            $path,
            new Property\Resource($resource),
            $deprecated,
            $input,
            $responses,
        );
    }
}
