<?php

namespace App\Shared\Infrastructure\Validation;

use App\Shared\Infrastructure\Validation\Exception\SchemaValidationException;
use Opis\JsonSchema\Validator;
use stdClass;

final class JsonSchemaValidator
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public function __invoke(stdClass $data, string $jsonSchema): void
    {
        $schema = json_decode($jsonSchema);

        $result = $this->validator->validate($data, $schema);

        if (!$result->isValid()) {
            throw new SchemaValidationException('Invalid JSON data according to schema.');
        }
    }
}
