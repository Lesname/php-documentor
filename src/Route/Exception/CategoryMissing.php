<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Exception;

use LesDocumentor\Exception\AbstractException;

/**
 * @psalm-immutable
 */
final class CategoryMissing extends AbstractException
{
    /**
     * @param array<mixed> $tags
     */
    public function __construct(public readonly array $tags)
    {
        parent::__construct("Missing category from tags: " . implode(', ', $tags));
    }
}
