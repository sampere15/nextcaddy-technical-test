<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\EmptyStringException;

abstract class StringValueObject extends BaseValueObject
{
    public function __construct(protected readonly string $value)
    {
        $this->assertNotEmpty($value);
    }

    /**
     * Checks that the string is not empty.
     */
    private function assertNotEmpty(string $value): void
    {
        if (empty($value)) {
            throw new EmptyStringException();
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
