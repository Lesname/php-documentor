<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property;

use Override;
use RuntimeException;
use LesValueObject\String\AbstractStringValueObject;
use LesDocumentor\Route\Document\Property\Exception\UnprocessableFilename;

/**
 * @psalm-immutable
 */
final class Path extends AbstractStringValueObject
{
    /** @var non-empty-string */
    private readonly string $resource;
    /** @var non-empty-string */
    private readonly string $action;

    public function __construct(string $string)
    {
        parent::__construct($string);

        $pathParts = explode('/', $string);
        $filename = $pathParts[array_key_last($pathParts)];

        $fileParts = explode('.', $filename);

        if (count($fileParts) < 2) {
            throw new UnprocessableFilename($filename);
        }

        $lastKey = array_key_last($fileParts);
        $action = $fileParts[$lastKey];
        unset($fileParts[$lastKey]);

        if ($action === '') {
            throw new RuntimeException();
        }

        $this->action = $action;

        $resource = implode('.', $fileParts);

        if ($resource === '') {
            throw new RuntimeException();
        }

        $this->resource = $resource;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public static function getMinimumLength(): int
    {
        return 1;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public static function getMaximumLength(): int
    {
        return 255;
    }

    /**
     * @return non-empty-string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @return non-empty-string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
