<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Wrapper;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;
use LesDocumentor\Type\Document\Wrapper\ResultsTypeDocumentWrapper;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Type\Document\Wrapper\ResultsTypeDocumentWrapper::class)]
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
                new Property(new ExactKey('results'), $doc),
                new Property(
                    new ExactKey('meta'),
                    new CompositeTypeDocument(
                        [
                            new Property(
                                new ExactKey('total'),
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
