<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document\Wrapper;

use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\Wrapper\ResultsTypeDocumentWrapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\Wrapper\ResultsTypeDocumentWrapper
 */
final class ResultsTypeDocumentWrapperTest extends TestCase
{
    public function testWrap(): void
    {
        $doc = $this->createMock(TypeDocument::class);

        $wrapper = new ResultsTypeDocumentWrapper();

        $result = $wrapper->wrap($doc);

        self::assertInstanceOf(CompositeTypeDocument::class, $result);
        self::assertEquals(
            [
                'results' => new Property($doc),
                'meta' => new Property(
                    new CompositeTypeDocument(
                        [
                            'total' => new Property(
                                new NumberTypeDocument(
                                    new Range(0, PHP_INT_MAX),
                                    0,
                                    null,
                                ),
                            ),
                        ],
                    ),
                ),
            ],
            $result->properties,
        );
    }
}
