<?php
namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\BaseValueObject;

abstract class BooleanValueObject extends BaseValueObject
{
    public function __construct(protected readonly bool $value)
    {
        parent::__construct($value);
    }

    public function value(): bool
    {
        return $this->value;
    }
}