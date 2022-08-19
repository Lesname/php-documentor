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
use LessDocumentor\Type\MethodInputTypeDocumentor;
use LessDocumentor\Type\ObjectInputTypeDocumentor;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

final class MezzioRouteInputDocumentor implements RouteInputDocumentor
{
    /**
     * @param array<mixed> $route
     *
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    public function document(array $route): TypeDocument
    {
        assert(isset($route['middleware']) && is_string($route['middleware']) && class_exists($route['middleware']));

        $handler = new ReflectionClass($route['middleware']);

        if (AttributeHelper::hasAttribute($handler, DocInput::class)) {
            $document = $this->documentDocInput($handler);
        } elseif (isset($route['input'])) {
            assert(is_string($route['input']) && class_exists($route['input']));
            $document = $this->documentValueObject($route['input']);
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

            $document = (new MethodInputTypeDocumentor())
                ->document($method);
        }

        assert($document instanceof CompositeTypeDocument);

        $properties = $document->properties;

        if (AttributeHelper::hasAttribute($handler, DocInputProvided::class)) {
            $attribute = AttributeHelper::getAttribute($handler, DocInputProvided::class);

            foreach ($attribute->keys as $key) {
                unset($properties[$key]);
            }
        }

        // array values required to "reset" all keys
        return new CompositeTypeDocument($properties);
    }

    /**
     * @param ReflectionClass<object> $class
     *
     * @throws MissingAttribute
     */
    private function documentDocInput(ReflectionClass $class): CompositeTypeDocument
    {
        $attribute = AttributeHelper::getAttribute($class, DocInput::class);

        return $this->documentValueObject($attribute->input);
    }

    /**
     * @param class-string $event
     */
    private function documentValueObject(string $event): CompositeTypeDocument
    {
        $objInputDocumentor = new ObjectInputTypeDocumentor();

        $document = $objInputDocumentor->document($event);
        assert($document instanceof CompositeTypeDocument);

        return $document;
    }
}
