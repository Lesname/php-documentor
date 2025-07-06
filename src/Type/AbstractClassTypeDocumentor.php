<?php
declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use BackedEnum;
use ReflectionClass;
use ReflectionException;
use LesValueObject\ValueObject;
use LesDocumentor\Helper\AttributeHelper;
use LesDocumentor\Type\Attribute\DocFormat;
use LesValueObject\String\Exception\TooLong;
use LesValueObject\String\StringValueObject;
use LesValueObject\Number\NumberValueObject;
use LesValueObject\String\Exception\TooShort;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Attribute\DocMaxDepth;
use LesDocumentor\Type\Document\String\Length;
use LesDocumentor\Type\Document\String\Pattern;
use LesDocumentor\Type\Document\Collection\Size;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Document\EnumTypeDocument;
use LesDocumentor\Route\Exception\MissingAttribute;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\StringTypeDocument;
use LesDocumentor\Type\Document\Composite\Property;
use LesValueObject\Collection\CollectionValueObject;
use LesDocumentor\Type\Document\Composite\Key\AnyKey;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\CollectionTypeDocument;
use LesValueObject\Composite\DynamicCompositeValueObject;
use LesValueObject\Composite\Signature\SignatureCompositeValueObject;
use LesValueObject\String\Format\AbstractRegexStringFormatValueObject;

abstract class AbstractClassTypeDocumentor implements TypeDocumentor
{
    /**
     * @psalm-assert-if-true class-string $input
     */
    #[Override]
    public function canDocument(mixed $input): bool
    {
        return is_string($input) && class_exists($input);
    }

    /**
     * @throws MissingAttribute
     * @throws ReflectionException
     * @throws TooLong
     * @throws TooShort
     * @throws UnexpectedInput
     */
    #[Override]
    public function document(mixed $input): TypeDocument
    {
        if (!$this->canDocument($input)) {
            throw new UnexpectedInput('class-string', $input);
        }

        if ($input === DynamicCompositeValueObject::class) {
            return new CompositeTypeDocument([], true);
        }

        return match (true) {
            is_subclass_of($input, StringValueObject::class) => $this->documentStringValueObject($input),
            is_subclass_of($input, NumberValueObject::class) => $this->documentNumberValueObject($input),
            is_subclass_of($input, CollectionValueObject::class) => $this->documentCollectionValueObject($input),
            is_subclass_of($input, BackedEnum::class) => $this->documentEnum($input),
            is_subclass_of($input, SignatureCompositeValueObject::class) => $this->documentSignatureValueValueObject($input),
            default => $this->documentClass($input),
        };
    }

    /**
     * @param class-string<CollectionValueObject<ValueObject>> $class
     *
     * @throws ReflectionException
     */
    protected function documentCollectionValueObject(string $class): TypeDocument
    {
        return (new ReflectionClass(CollectionTypeDocument::class))
            ->newLazyProxy(
                function () use ($class) {
                    $typeDocument = new CollectionTypeDocument(
                        $this->document($class::getItemType()),
                        new Size(
                            $class::getMinimumSize(),
                            $class::getMaximumSize(),
                        ),
                        $class,
                    );

                    $reflector = new  ReflectionClass($class);

                    if (AttributeHelper::hasAttribute($reflector, DocMaxDepth::class)) {
                        $attribute = AttributeHelper::getAttribute($reflector, DocMaxDepth::class);

                        return $typeDocument->withMaxDepth($attribute->maxDepth);
                    }

                    return $typeDocument;
                },
            );
    }

    /**
     * @param class-string<BackedEnum> $class
     */
    protected function documentEnum(string $class): TypeDocument
    {
        return new EnumTypeDocument(
            array_map(
                static fn (BackedEnum $enum): string => (string)$enum->value,
                $class::cases(),
            ),
            $class,
        );
    }

    /**
     * @param class-string<NumberValueObject> $class
     *
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentNumberValueObject(string $class): TypeDocument
    {
        $refClass = new ReflectionClass($class);

        $format = AttributeHelper::hasAttribute($refClass, DocFormat::class)
            ? AttributeHelper::getAttribute($refClass, DocFormat::class)->name
            : null;

        return new NumberTypeDocument(
            new Range(
                $class::getMinimumValue(),
                $class::getMaximumValue(),
            ),
            $class::getMultipleOf(),
            $format,
            $class,
        );
    }

    /**
     * @param class-string<StringValueObject> $class
     *
     * @throws TooLong
     * @throws TooShort
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentStringValueObject(string $class): TypeDocument
    {
        $refClass = new ReflectionClass($class);

        if (AttributeHelper::hasAttribute($refClass, DocFormat::class)) {
            $format = AttributeHelper::getAttribute($refClass, DocFormat::class)->name;
        } else {
            $format = null;
        }

        $pattern = is_subclass_of($class, AbstractRegexStringFormatValueObject::class)
            ? new Pattern($class::getRegularExpression())
            : null;

        return new StringTypeDocument(
            new Length($class::getMinimumLength(), $class::getMaximumLength()),
            $format,
            $pattern,
            $class,
        );
    }

    /**
     * @param class-string<SignatureCompositeValueObject<ValueObject>> $class
     *
     * @throws TooLong
     * @throws TooShort
     * @throws UnexpectedInput
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentSignatureValueValueObject(string $class): TypeDocument
    {
        return new CompositeTypeDocument(
            [
                new Property(
                    new AnyKey(),
                    $this->document($class::getSignature()),
                ),
            ],
            reference: $class
        );
    }

    /**
     * @param class-string $class
     */
    abstract protected function documentClass(string $class): TypeDocument;
}
