<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Wrapper;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;
use LesDocumentor\Type\Document\Wrapper\ResultTypeDocumentWrapper;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Type\Document\Wrapper\ResultTypeDocumentWrapper::class)]
final class ResultTypeDocumentWrapperTest extends TestCase
{
    public function testWrap(): void
    {
        $doc = $this->createMock(TypeDocument::class);

        $wrapper = new ResultTypeDocumentWrapper();

        $result = $wrapper->wrap($doc);

        self::assertInstanceOf(CompositeTypeDocument::class, $result);
        self::assertEquals([new Property(new ExactKey('result'), $doc)], $result->properties);
    }
}
