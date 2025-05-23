<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Document\RouteDocument;
use LesDocumentor\Route\Document\Property\Method;
use LesDocumentor\Route\Document\Property\Path;
use LesDocumentor\Route\Document\Property\Response;
use LesDocumentor\Route\Document\Property\Resource;
use LesDocumentor\Route\Document\Property\ResponseCode;
use LesDocumentor\Route\OpenApiRouteDocumentor;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\EnumTypeDocument;
use LesDocumentor\Type\Document\ReferenceTypeDocument;
use PHPUnit\Framework\TestCase;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;

#[CoversClass(\LesDocumentor\Route\OpenApiRouteDocumentor::class)]
final class OpenApiRouteDocumentorTest extends TestCase
{
    public function testDocument(): void
    {
        $route = [
            'post' => [
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
            ],
        ];

        $routeDocumentor = new OpenApiRouteDocumentor();

        self::assertEquals(
            new RouteDocument(
                Method::Post,
                new Path('/foo.bar'),
                new Resource('foo'),
                null,
                new CompositeTypeDocument(
                    [
                        new Property(
                            new ExactKey('fiz'),
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
                                new Property(
                                    new ExactKey('id'),
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
