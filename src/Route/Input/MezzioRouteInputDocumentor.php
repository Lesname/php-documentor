<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Input;

use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Route\Attribute\DocHttpProxy;
use LessDocumentor\Route\Attribute\DocInput;
use LessDocumentor\Route\Attribute\DocInputProvided;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\ObjectInputTypeDocumentor;
use LessValueObject\Number\Exception\MaxOutBounds;
use LessValueObject\Number\Exception\MinOutBounds;
use LessValueObject\Number\Exception\PrecisionOutBounds;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

final class MezzioRouteInputDocumentor implements RouteInputDocumentor
{
    /**
     * @param array<mixed> $route
     *
     * @throws MissingAttribute
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     */
    public function document(array $route): TypeDocument
    {
        assert(isset($route['middleware']) && is_string($route['middleware']) && class_exists($route['middleware']));

        $handler = new ReflectionClass($route['middleware']);

        $objInputDocumentor = new ObjectInputTypeDocumentor();

        if (AttributeHelper::hasAttribute($handler, DocInput::class)) {
            $attribute = AttributeHelper::getAttribute($handler, DocInput::class);
            $input = $objInputDocumentor->document($attribute->input);
            assert($input instanceof CompositeTypeDocument);

            $properties = $input->properties;
        } elseif (isset($route['event'])) {
            assert(is_string($route['event']) && class_exists($route['event']));

            $input = $objInputDocumentor->document($route['event']);
            assert($input instanceof CompositeTypeDocument);

            $properties = $input->properties;
        } else {
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

            $properties = [];

            foreach ($method->getParameters() as $parameter) {
                $type = $parameter->getType();
                assert($type instanceof ReflectionNamedType);
                assert($type->isBuiltin() === false);

                $class = $type->getName();
                assert(class_exists($class));

                $properties[$parameter->getName()] = $objInputDocumentor
                    ->document($class)
                    ->withRequired(!$type->allowsNull());
            }
        }

        if (AttributeHelper::hasAttribute($handler, DocInputProvided::class)) {
            $attribute = AttributeHelper::getAttribute($handler, DocInputProvided::class);

            foreach ($attribute->keys as $key) {
                unset($properties[$key]);
            }
        }

        return new CompositeTypeDocument($properties, null);
    }
}
