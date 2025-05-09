# Les Documentor

Les Documentor is a library that helps read sources and transform it to type documents.

Current sources supported:
- [ValueObjects](https://packagist.org/packages/lesname/value-object)
- OpenApi

## Example

### Value object

```<?php
use LesDocumentor\Type\ClassPropertiesTypeDocumentor;
use LesValueObject\String\Format\EmailAddress;

$documentor = new ClassPropertiesTypeDocumentor();

$document = $documentor->document(EmailAddress::class);
```

Result:
```
StringTypeDocument
- reference: LesValueObject\String\Format\EmailAddress
- description: null
- nullable: false
- length
    - minimal: 5
    - maximal: 255
- format: null
- pattern: null
```

### OpenApi

```<?php
use LesDocumentor\Type\OpenApiTypeDocumentor;

$documentor = new OpenApiTypeDocumentor();

$document = $documentor->document(
    [
            "type" => "integer",
            "minimum" => 100,
            "maximum" => 600
    ],
);
```

Result:
```
NumberTypeDocument
- reference: null
- description: null
- nullable: false
- range
    - minimal: 100
    - maximal 500
- multipleOf: null
- format: null
```