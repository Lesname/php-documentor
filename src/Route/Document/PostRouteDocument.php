<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document;

use LessDocumentor\Route\Document\Property\Category;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Route\Document\Property\Path;
use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
final class PostRouteDocument implements RouteDocument
{
    /**
     * @param array<int, Property\Response> $responses
     */
    public function __construct(
        private readonly Category $category,
        private readonly Path $path,
        private readonly string $resource,
        private readonly ?Deprecated $deprecated,
        private readonly TypeDocument $input,
        private readonly array $responses,
    ) {}

    public function getMethod(): Method
    {
        return Method::Post;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getPath(): Path
    {
        return $this->path;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getDeprecated(): ?Deprecated
    {
        return $this->deprecated;
    }

    public function getInput(): TypeDocument
    {
        return $this->input;
    }

    public function getRespones(): array
    {
        return $this->responses;
    }
}
