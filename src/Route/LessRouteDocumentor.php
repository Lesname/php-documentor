<?php
declare(strict_types=1);

namespace LessDocumentor\Route;

use LessValueObject\Composite\Paginate;
use LessDocumentor\Helper\AttributeHelper;
use LessValueObject\String\Exception\TooLong;
use LessValueObject\String\Exception\TooShort;
use LessDocumentor\Route\Attribute\DocHttpProxy;
use LessValueObject\Number\Int\Paginate\PerPage;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Route\Attribute\DocHttpResponse;
use LessDocumentor\Route\Attribute\DocResource;
use LessDocumentor\Route\Document\Property\Category;
use LessDocumentor\Route\Document\Property\Resource;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Path;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Route\Document\RouteDocument;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Route\Input\MezzioRouteInputDocumentor;
use LessDocumentor\Route\Input\RouteInputDocumentor;
use LessDocumentor\Type\Document\AnyTypeDocument;
use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\Collection\Size;
use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper;
use LessDocumentor\Type\ObjectOutputTypeDocumentor;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;
use Traversable;

final class LessRouteDocumentor implements RouteDocumentor
{
    private ?RouteInputDocumentor $routeInputDocumentor = null;

    /**
     * @param array<mixed> $route
     *
     * @return RouteDocument
     * @throws TooLong
     * @throws TooShort
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    public function document(array $route): RouteDocument
    {
        assert(isset($route['path']) && is_string($route['path']));
        assert(isset($route['resource']) && is_string($route['resource']));
        assert($route['category'] instanceof Category);

        $deprecated = null;

        $routeAlternate = $route['alternate'] ?? null;
        assert($routeAlternate === null || is_string($routeAlternate));

        $routeDeprecated = $route['deprecated'] ?? null;
        assert($routeDeprecated === null || is_string($routeDeprecated));

        if (is_string($routeAlternate) || is_string($routeDeprecated)) {
            $deprecated = new Deprecated($routeAlternate, $routeDeprecated);
        }

        return new RouteDocument(
            Method::Post,
            $route['category'],
            new Path($route['path']),
            new Resource($route['resource']),
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

            $proxyClass = new ReflectionClass($proxy['class']);
            $proxyMethod = $proxyClass->getMethod($proxy['method']);

            $return = $proxyMethod->getReturnType();
            assert($return instanceof ReflectionNamedType);

            if (is_subclass_of($return->getName(), Traversable::class)) {
                $hasPaginate = false;

                foreach ($proxyMethod->getParameters() as $parameter) {
                    $type = $parameter->getType();

                    if ($type instanceof ReflectionNamedType && $type->getName() === Paginate::class) {
                        $hasPaginate = true;

                        break;
                    }
                }

                $attribute = AttributeHelper::getAttribute($proxyClass, DocResource::class);
                $output = new CollectionTypeDocument(
                    $objInputDocumentor->document($attribute->resource),
                    $hasPaginate ? new Size(max(0, (int)floor(PerPage::getMinimumValue())), (int)ceil(PerPage::getMaximumValue())) : null,
                    null,
                );
            } elseif (interface_exists($return->getName())) {
                $attribute = AttributeHelper::getAttribute($proxyClass, DocResource::class);
                $output = $objInputDocumentor->document($attribute->resource);
            } else {
                $returns = $return->getName();

                if (class_exists($returns)) {
                    $output = $objInputDocumentor->document($returns);
                } else {
                    $output = match ($returns) {
                        'array' => new CompositeTypeDocument([], true),
                        'bool' => new BoolTypeDocument(),
                        'float' => new NumberTypeDocument(null, null, null),
                        'int' => new NumberTypeDocument(null, 1),
                        'mixed' => new AnyTypeDocument(),
                        'string' => new StringTypeDocument(null),
                        default => throw new RuntimeException("Unknown type '{$returns}'"),
                    };
                }
            }
        } else {
            $attribute = AttributeHelper::getAttribute($handler, DocHttpProxy::class);
            $method = new ReflectionMethod($attribute->class, $attribute->method);

            $return = $method->getReturnType();
            assert($return instanceof ReflectionNamedType);
            assert($return->isBuiltin() === false);

            if (interface_exists($return->getName())) {
                $class = AttributeHelper::getAttribute(
                    new ReflectionClass($attribute->class),
                    DocResource::class,
                )->resource;
            } else {
                $class = $return->getName();
                assert(class_exists($class));
            }

            $output = $objInputDocumentor->document($class);
        }

        if (AttributeHelper::hasAttribute($handler, DocTypeWrapper::class)) {
            $attribute = AttributeHelper::getAttribute($handler, DocTypeWrapper::class);
            $wrapper = new $attribute->typeWrapper();

            $output = $wrapper->wrap($output);
        }

        return [new Response(new ResponseCode(200), $output)];
    }
}
