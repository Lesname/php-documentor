<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document;

use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
final class PostRouteDocument implements RouteDocument
{
    /**
     * @param array<Property\Response> $responses
     */
    public function __construct(
        private string $path,
        private string $resource,
        private ?Deprecated $deprecated,
        private TypeDocument $input,
        private array $responses,
    ) {}

    public function getMethod(): Method
    {
        return Method::post();
    }

    public function getPath(): string
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
