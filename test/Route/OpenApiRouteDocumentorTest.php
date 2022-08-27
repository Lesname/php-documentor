<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route;

use LessDocumentor\Route\Document\PostRouteDocument;
use LessDocumentor\Route\Document\Property\Category;
use LessDocumentor\Route\Document\Property\Path;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Route\OpenApiRouteDocumentor;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Type\Document\ReferenceTypeDocument;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\OpenApiRouteDocumentor
 */
final class OpenApiRouteDocumentorTest extends TestCase
{
    public function testDocument(): void
    {
        $route = [
            '/foo.bar' => [
                'post' => [
                    'tags' => [
                        'bar',
                        'command',
                    ],
                    'deprecated' => false,
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'additionalProperties' => false,
                                    'properties' => [
                                        'fiz' => [
                                            'type' => 'string',
                                            'enum' => [
                                                'foo',
                                                'bar',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '204' => [
                            'description' => 'Call successfull, nothing to output',
                        ],
                        '201' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'additionalProperties' => false,
                                        'properties' => [
                                            'id' => [
                                                '$ref' => '#/components/schemas/Identifier',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $routeDocumentor = new OpenApiRouteDocumentor();

        self::assertEquals(
            new PostRouteDocument(
                Category::Command,
                new Path('/foo.bar'),
                'foo',
                null,
                new CompositeTypeDocument(
                    [
                        'fiz' => new Property(
                            new EnumTypeDocument(
                                [
                                    'foo',
                                    'bar',
                                ],
                            ),
                            false,
                        ),
                    ],
                ),
                [
                    new Response(
                        new ResponseCode(204),
                        null,
                    ),
                    new Response(
                        new ResponseCode(201),
                        new CompositeTypeDocument(
                            [
                                'id' => new Property(
                                    new ReferenceTypeDocument('#/components/schemas/Identifier'),
                                    false,
                                ),
                            ],
                        ),
                    ),
                ],
            ),
            $routeDocumentor->document($route),
        );
    }
}
