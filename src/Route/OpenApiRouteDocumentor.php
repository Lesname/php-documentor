<?php
declare(strict_types=1);

namespace LessDocumentor\Route;

use LessDocumentor\Route\Document\PostRouteDocument;
use LessDocumentor\Route\Document\Property\Category;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Route\Document\RouteDocument;
use LessDocumentor\Type\OpenApiTypeDocumentor;
use RuntimeException;

final class OpenApiRouteDocumentor implements RouteDocumentor
{
    /**
     * @param array<mixed> $route
     */
    public function document(array $route): RouteDocument
    {
        if (count($route) !== 1) {
            throw new RuntimeException();
        }

        $path = array_key_first($route);
        assert(is_string($path));

        $sub = $route[$path];

        assert(is_array($sub));

        if (count($sub) !== 1) {
            throw new RuntimeException();
        }

        $method = array_key_first($sub);
        $schema = $sub[$method];
        assert(is_array($schema));

        $deprecated = ($schema['deprecated'] ?? false)
            ? new Deprecated('', '')
            : null;

        assert(is_array($schema['tags']));
        $category = Category::fromTags($schema['tags']);

        $position = strrpos($path, '/');
        $resource = is_int($position)
            ? substr($path, $position + 1)
            : $path;

        $position = strrpos($resource, '.');
        $resource = is_int($position)
            ? substr($resource, 0, $position)
            : $resource;

        assert(is_array($schema['requestBody']));
        assert(is_array($schema['requestBody']['content']));
        assert(is_array($schema['requestBody']['content']['application/json']));
        assert(is_array($schema['requestBody']['content']['application/json']['schema']));

        $typeDocumentor = new OpenApiTypeDocumentor();

        $input = $typeDocumentor
            ->document($schema['requestBody']['content']['application/json']['schema']);

        $responses = [];

        assert(is_array($schema['responses']));

        foreach ($schema['responses'] as $code => $schemaResponse) {
            assert(is_int($code));
            assert(is_array($schemaResponse));

            if (!isset($schemaResponse['content'])) {
                $responses[] = new Response(
                    new ResponseCode($code),
                    null,
                );

                continue;
            }

            assert(is_array($schemaResponse['content']));
            assert(is_array($schemaResponse['content']['application/json']));
            assert(is_array($schemaResponse['content']['application/json']['schema']));

            $responses[] = new Response(
                new ResponseCode($code),
                $typeDocumentor->document($schemaResponse['content']['application/json']['schema']),
            );
        }

        return new PostRouteDocument($category, $path, $resource, $deprecated, $input, $responses);
    }
}
