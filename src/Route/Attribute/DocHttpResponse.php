<?php

declare(strict_types=1);

namespace LesDocumentor\Route\Attribute;

use Attribute;
use LesValueObject\ValueObject;

#[Attribute(Attribute::TARGET_CLASS)]
final class DocHttpResponse
{
    public readonly int $code;

    /**
     * @param class-string<ValueObject>|null $output
     */
    public function __construct(public readonly ?string $output = null, ?int $code = null)
    {
        $this->code = $code ?? ($this->output ? 200 : 204);
    }
}
