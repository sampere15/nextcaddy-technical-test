<?php
namespace App\Aggregate\Player\Domain\ValueObject;

use App\Shared\Domain\ValueObject\DateTimeValueObject;

final class PlayerBirthdate extends DateTimeValueObject
{
    public function __toString(): string
    {
        return $this->value()->format('Y-m-d');
    }
}
