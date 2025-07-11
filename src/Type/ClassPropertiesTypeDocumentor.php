<?php
declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use ReflectionClass;
use ReflectionProperty;
use LesDocumentor\Helper\AttributeHelper;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Attribute\DocMaxDepth;
use LesDocumentor\Type\Exception\UnknownPropertyType;
use LesDocumentor\Type\Attribute\DocDeprecated;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;

final class ClassPropertiesTypeDocumentor extends AbstractClassTypeDocumentor
{
    private readonly TypeDocumentor $hintTypeDocumentor;

    public function __construct(?TypeDocumentor $hintTypeDocumentor = null)
    {
        $this->hintTypeDocumentor = $hintTypeDocumentor ?? new HintTypeDocumentor($this);
    }

    /**
     * @param class-string $class
     */
    #[Override]
    protected function documentClass(string $class): TypeDocument
    {
        return (new ReflectionClass(CompositeTypeDocument::class))
            ->newLazyProxy(
                function () use ($class) {
                    $classReflection = new ReflectionClass($class);
                    $properties = [];

                    foreach ($classReflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                        $propertyType = $property->getType();

                        if ($propertyType === null) {
                            throw new UnknownPropertyType($property->getName());
                        }

                        $properties[] = new Property(
                            new ExactKey($property->getName()),
                            $this->hintTypeDocumentor->document($propertyType),
                            deprecated: AttributeHelper::hasAttribute($property, DocDeprecated::class),
                        );
                    }

                    $typeDocument = new CompositeTypeDocument($properties, reference: $class);
                    $reflector = new ReflectionClass($class);

                    if (AttributeHelper::hasAttribute($reflector, DocMaxDepth::class)) {
                        $attribute = AttributeHelper::getAttribute($reflector, DocMaxDepth::class);

                        return $typeDocument->withMaxDepth($attribute->maxDepth);
                    }

                    return $typeDocument;
                },
            );
    }
}
