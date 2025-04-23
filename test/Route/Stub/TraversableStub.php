<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Stub;

use Iterator;

final class TraversableStub implements Iterator
{
    public function current(): mixed
    {
    }

    public function next(): void
    {
    }

    public function key(): mixed
    {
    }

    public function valid(): bool
    {
    }

    public function rewind(): void
    {
    }
}
