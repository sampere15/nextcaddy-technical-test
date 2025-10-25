<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\GenderEnumException;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public static function fromString(string $gender): self
    {
        return match (strtolower($gender)) {
            'male' => self::MALE,
            'female' => self::FEMALE,
            default => throw new GenderEnumException("Invalid gender: $gender"),
        };
    }

    public function value(): string
    {
        return $this->value;
    }

    public function shortCode(): string
    {
        return match ($this) {
            self::MALE => 'M',
            self::FEMALE => 'F',
        };
    }
}
