<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Wrapper;

use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;
use LesDocumentor\Type\Document\Wrapper\ResultTypeDocumentWrapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\Wrapper\ResultTypeDocumentWrapper
 */
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
