<?php
declare(strict_types=1);

namespace LessDocumentor\Route;

use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Route\Attribute\DocHttpProxy;
use LessDocumentor\Route\Attribute\DocHttpResponse;
use LessDocumentor\Route\Document\PostRouteDocument;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Route\Document\RouteDocument;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Route\Input\MezzioRouteInputDocumentor;
use LessDocumentor\Route\Input\RouteInputDocumentor;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\ObjectOutputTypeDocumentor;
use LessValueObject\Number\Exception\MaxOutBounds;
use LessValueObject\Number\Exception\MinOutBounds;
use LessValueObject\Number\Exception\PrecisionOutBounds;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

final class MezzioRouteDocumentor implements RouteDocumentor
{
    private ?RouteInputDocumentor $routeInputDocumentor = null;

    /**
     * @param array<mixed> $route
     *
     * @throws MissingAttribute
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     */
    public function document(array $route): RouteDocument
    {
        assert(isset($route['path']) && is_string($route['path']));
        assert(isset($route['resource']) && is_string($route['resource']));

        $deprecated = null;

        if (isset($route['alternate']) || isset($route['deprecated'])) {
            assert(!isset($route['deprecated']) || is_string($route['deprecated']));
            assert(!isset($route['alternate']) || is_string($route['alternate']));

            $deprecated = new Deprecated(
                $route['alternate'] ?? null,
                $route['deprecated'] ?? null,
            );
        }

        return new PostRouteDocument(
            $route['path'],
            $route['resource'],
            $deprecated,
            $this->getRouteInputDocumentor()->document($route),
            $this->documentResponses($route),
        );
    }

    /**
     * @return RouteInputDocumentor
     */
    public function getRouteInputDocumentor(): RouteInputDocumentor
    {
        return $this->routeInputDocumentor ??= new MezzioRouteInputDocumentor();
    }

    /**
     * @param array<mixed> $route
     *
     * @return array<int, Response>
     *
     * @throws MissingAttribute
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     */
    private function documentResponses(array $route): array
    {
        assert(isset($route['middleware']) && is_string($route['middleware']) && class_exists($route['middleware']));

        $objInputDocumentor = new ObjectOutputTypeDocumentor();

        $handler = new ReflectionClass($route['middleware']);

        if (AttributeHelper::hasAttribute($handler, DocHttpResponse::class)) {
            $response = AttributeHelper::getAttribute($handler, DocHttpResponse::class);

            return [
                new Response(
                    new ResponseCode($response->code),
                    $response->output
                        ? $objInputDocumentor->document($response->output)
                        : null,
                ),
            ];
        }

        if (isset($route['proxy'])) {
            assert(is_array($route['proxy']));
            $proxy = $route['proxy'];

            assert(is_string($proxy['class']) && (interface_exists($proxy['class']) || class_exists($proxy['class'])));
            assert(is_string($proxy['method']));

            $method = new ReflectionMethod($proxy['class'], $proxy['method']);
        } else {
            $attribute = AttributeHelper::getAttribute($handler, DocHttpProxy::class);
            $method = new ReflectionMethod($attribute->class, $attribute->method);
        }

        $return = $method->getReturnType();
        assert($return instanceof ReflectionNamedType);
        assert($return->isBuiltin() === false);

        $class = $return->getName();
        assert(class_exists($class));

        return [
            new Response(
                new ResponseCode(200),
                $objInputDocumentor->document($class),
            ),
        ];
    }
}
