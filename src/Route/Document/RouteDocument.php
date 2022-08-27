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
interface RouteDocument
{
    public function getMethod(): Method;

    public function getCategory(): Category;

    public function getPath(): Path;

    public function getResource(): string;

    public function getDeprecated(): ?Deprecated;

    public function getInput(): TypeDocument;

    /**
     * @return array<Property\Response>
     */
    public function getRespones(): array;
}
