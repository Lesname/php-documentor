<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Document\Property;

use Override;
use LesValueObject\String\Exception\TooLong;
use LesValueObject\String\Exception\TooShort;
use LesValueObject\String\AbstractStringValueObject;
use LesDocumentor\Route\Document\Property\Exception\UnprocessableFilename;

/**
 * @psalm-immutable
 */
final class Path extends AbstractStringValueObject
{
    private readonly string $resource;

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

        $this->action = $action;
        $this->resource = implode('.', $fileParts);
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

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
