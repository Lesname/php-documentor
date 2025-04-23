<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Wrapper;

use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Wrapper\ResultsTypeDocumentWrapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\Wrapper\ResultsTypeDocumentWrapper
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
                                    1,
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
