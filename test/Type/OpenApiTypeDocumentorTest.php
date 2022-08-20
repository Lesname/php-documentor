<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessDocumentor\Type\Document\Collection\Size;
use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\ReferenceTypeDocument;
use LessDocumentor\Type\Document\String\Length;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\OpenApiTypeDocumentor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\OpenApiTypeDocumentor
 */
final class OpenApiTypeDocumentorTest extends TestCase
{
    public function testDocument(): void
    {
        $schema = [
            'type' => 'object',
            'additionalProperties' => false,
            'properties' => [
                'role' => [
                    'type' => 'string',
                    'enum' => [
                        'developer',
                        'customer',
                    ],
                ],
                'emailAddress' => [
                    '$ref' => '#/components/schemas/EmailAddress',
                ],
                'security' => [
                    'type' => [
                        'object',
                        'null'
                    ],
                    'deprecated' => true,
                    'additionalProperties' => true,
                    'properties' => [
                        'verification' => [
                            'type' => 'string',
                            'enum' => [
                                'none'
                            ],
                        ],
                    ],
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
                    'maxLength' => 30
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
                    'role' => new Property(
                        new EnumTypeDocument(
                            [
                                'developer',
                                'customer',
                            ],
                        ),
                    ),
                    'emailAddress' => new Property(
                        new ReferenceTypeDocument(
                            '#/components/schemas/EmailAddress',
                        ),
                    ),
                    'security' => new Property(
                        (new CompositeTypeDocument(
                            [
                                'verification' => new Property(
                                    new EnumTypeDocument(['none']),
                                    false,
                                ),
                            ],
                            true,
                        ))->withNullable()->withDeprecated('deprecated'),
                        deprecated: true,
                    ),
                    'foo' => new Property(
                        (new ReferenceTypeDocument("#/components/schemas/Occurred"))
                            ->withNullable()
                            ->withDeprecated('deprecated'),
                        false,
                        deprecated: true,
                    ),
                    'fiz' => new Property(
                        (                        new NumberTypeDocument(
                            new Range(-321, 123),
                            1
                        ))->withDeprecated('deprecated'),
                        false,
                        deprecated: true,
                    ),
                    'bar' => new Property(
                        new StringTypeDocument(
                            new Length(3, 30),
                        ),
                        false,
                    ),
                    'biz' => new Property(
                        new CollectionTypeDocument(
                            new NumberTypeDocument(
                                new Range(
                                    -321,
                                    123.4,
                                ),
                                3,
                            ),
                            new Size(1, 99),
                        ),
                        false,
                    ),
                ],
            ),
            $documentor->document($schema),
        );
    }
}
