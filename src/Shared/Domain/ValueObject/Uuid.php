<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\UuidInvalidException;

abstract class Uuid extends BaseValueObject
{
    public const TAM = 36;

    public function __construct(protected readonly string $value)
    {
        $this->assertFormat($value);
    }

    private function assertFormat(string $value): void
    {
        $tam = self::TAM;
        if (!preg_match("/^[0-9a-fA-F-]{{$tam}}$/", $value)) {
            throw new UuidInvalidException("Invalid UUID format: $value");
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
