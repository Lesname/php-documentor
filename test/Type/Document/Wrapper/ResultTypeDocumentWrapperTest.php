<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document\Wrapper;

use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\Wrapper\ResultTypeDocumentWrapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\Wrapper\ResultTypeDocumentWrapper
 */
final class ResultTypeDocumentWrapperTest extends TestCase
{
    public function testWrap(): void
    {
        $doc = $this->createMock(TypeDocument::class);

        $wrapper = new ResultTypeDocumentWrapper();

        $result = $wrapper->wrap($doc);

        self::assertInstanceOf(CompositeTypeDocument::class, $result);
        self::assertEquals(['result' => new Property($doc)], $result->properties);
    }
}
