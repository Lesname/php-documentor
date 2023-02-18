<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document;

use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Composite\AbstractCompositeValueObject;

/**
 * Will become final in next release
 *
 * @todo make final
 *
 * @psalm-immutable
 */
class RouteDocument extends AbstractCompositeValueObject
{
    /**
     * @param array<int, Property\Response> $responses
     */
    public function __construct(
        public readonly Property\Method $method,
        public readonly Property\Category $category,
        public readonly Property\Path $path,
        public readonly Property\Resource $resource,
        public readonly ?Property\Deprecated $deprecated,
        public readonly TypeDocument $input,
        public readonly array $responses,
    ) {}

    /**
     * @deprecated use property
     */
    public function getMethod(): Property\Method
    {
        return $this->method;
    }

    /**
     * @deprecated use property
     */
    public function getCategory(): Property\Category
    {
        return $this->category;
    }

    /**
     * @deprecated use property
     */
    public function getPath(): Property\Path
    {
        return $this->path;
    }

    /**
     * @deprecated use property
     */
    public function getResource(): string
    {
        return (string)$this->resource;
    }

    /**
     * @deprecated use property
     */
    public function getDeprecated(): ?Property\Deprecated
    {
        return $this->deprecated;
    }

    /**
     * @deprecated use property
     */
    public function getInput(): TypeDocument
    {
        return $this->input;
    }

    /**
     * @return array<Property\Response>
     */
    public function getRespones(): array
    {
        return $this->responses;
    }
}
