<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type;

use LesDocumentor\Type\Document\String\Pattern;
use LesDocumentor\Type\Document\Collection\Size;
use LesDocumentor\Type\Document\CollectionTypeDocument;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\EnumTypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\ReferenceTypeDocument;
use LesDocumentor\Type\Document\String\Length;
use LesDocumentor\Type\Document\StringTypeDocument;
use LesDocumentor\Type\OpenApiTypeDocumentor;
use PHPUnit\Framework\TestCase;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;
use LesDocumentor\Type\Document\Composite\Key\RegexKey;

/**
 * @covers \LesDocumentor\Type\OpenApiTypeDocumentor
 */
final class OpenApiTypeDocumentorTest extends TestCase
{
    public function testDocument(): void
    {
        $schema = [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'emailAddress' => [
                    '$ref' => '#/components/schemas/EmailAddress',
                ],
                'foo' => [
                    'deprecated' => true,
                    'anyOf' => [
                        ['$ref' => "#/components/schemas/Occurred"],
                        ['type' => 'null'],
                    ],
                ],
                'fiz' => [
                    'type' => 'integer',
                    'multipleOf' => 1,
                    'minimum' => -321,
                    'maximum' => 123,
                    'deprecated' => true,
                ],
                'bar' => [
                    'type' => 'string',
                    'minLength' => 3,
                    'maxLength' => 30,
                    'pattern' => '/^.{1,30}$/'
                ],
                'biz' => [
                    'type' => 'array',
                    'minItems' => 1,
                    'maxItems' => 99,
                    'items' => [
                        'type' => 'number',
                        'multipleOf' => 0.01,
                        'minimum' => -321,
                        'maximum' => 123.4,
                    ],
                ],
            ],
            'patternProperties' => [
                '^S_' => [
                    'type' => 'string',
                    'enum' => [
                        's',
                        'S',
                    ],
                ],
            ],
            'required' => [
                'role',
                'emailAddress',
                'security',
            ],
        ];

        $documentor = new OpenApiTypeDocumentor();

        self::assertEquals(
            new CompositeTypeDocument(
                [
                    new Property(
                        new ExactKey('emailAddress'),
                        new ReferenceTypeDocument(
                            '#/components/schemas/EmailAddress',
                        ),
                    ),
                    new Property(
                        new ExactKey('foo'),
                        (new ReferenceTypeDocument("#/components/schemas/Occurred"))
                            ->withNullable(),
                        false,
                        deprecated: true,
                    ),
                    new Property(
                        new ExactKey('fiz'),
                        new NumberTypeDocument(
                            new Range(-321, 123),
                            1,
                        ),
                        false,
                        deprecated: true,
                    ),
                    new Property(
                        new ExactKey('bar'),
                        new StringTypeDocument(
                            new Length(3, 30),
                            pattern: new Pattern('/^.{1,30}$/'),
                        ),
                        false,
                    ),
                    new Property(
                        new ExactKey('biz'),
                        new CollectionTypeDocument(
                            new NumberTypeDocument(
                                new Range(
                                    -321,
                                    123.4,
                                ),
                                .01,
                            ),
                            new Size(1, 99),
                        ),
                        false,
                    ),
                    new Property(
                        new RegexKey('^S_'),
                        new EnumTypeDocument(
                            [
                                's',
                                'S',
                            ],
                        ),
                        required: false,
                    )
                ],
            ),
            $documentor->document($schema),
        );
    }
}
