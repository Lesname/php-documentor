<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document\Wrapper;

use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\Property\Range;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\Wrapper\ResultsTypeDocumentWrapper;
use LessValueObject\Number\Int\Unsigned;
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
                'results' => $doc,
                'meta' => new CompositeTypeDocument(
                    [
                        'total' => new NumberTypeDocument(
                            new Range(0, PHP_INT_MAX),
                            new Unsigned(0),
                            null,
                        ),
                    ],
                    null,
                ),
            ],
            $result->properties
        );
    }
}
