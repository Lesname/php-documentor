<?php
declare(strict_types=1);

namespace LesDocumentor\Route;

use Override;
use LesValueObject\String\Exception\TooLong;
use LesValueObject\String\Exception\TooShort;
use LesDocumentor\Route\Document\Property\Method;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Route\Document\Property\Resource;
use LesDocumentor\Route\Exception\CannotHandleRoute;
use LesDocumentor\Route\Document\Property\Deprecated;
use LesDocumentor\Route\Document\Property\Path;
use LesDocumentor\Route\Document\Property\Response;
use LesDocumentor\Route\Document\Property\ResponseCode;
use LesDocumentor\Route\Document\RouteDocument;
use LesDocumentor\Type\OpenApiTypeDocumentor;
use LesDocumentor\Route\Document\Property\Exception\InvalidResponseCode;

final class OpenApiRouteDocumentor implements RouteDocumentor
{
    /**
     * @param array<mixed> $route
     *
     * @throws CannotHandleRoute
     * @throws InvalidResponseCode
     * @throws UnexpectedInput
     * @throws TooLong
     * @throws TooShort
     */
    #[Override]
    public function document(array $route): RouteDocument
    {
        if (count($route) !== 1) {
            throw new CannotHandleRoute($route);
        }

        $path = array_key_first($route);
        assert(is_string($path));

        assert(is_array($route[$path]));
        assert(count($route[$path]) === 1);

        $method = array_key_first($route[$path]);
        assert(is_string($method));

        $schema = $route[$path][$method];
        assert(is_array($schema));

        $deprecated = isset($schema['deprecated']) && $schema['deprecated']
            ? new Deprecated('', '')
            : null;

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

        return new RouteDocument(
            Method::from($method),
            new Path($path),
            new Resource($resource),
            $deprecated,
            $input,
            $responses,
        );
    }
}
