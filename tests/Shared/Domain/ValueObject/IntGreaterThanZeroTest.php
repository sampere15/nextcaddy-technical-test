<?php

namespace App\Tests\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\IntGreaterThanZero;
use App\Tests\Shared\Domain\AbstractClassValueObjectTest;
use App\Shared\Domain\Exception\IntLessOrEqualToZeroException;

abstract class IntGreaterThanZeroTest extends AbstractClassValueObjectTest
{
    protected function getValueObjectClass(): string
    {
        return IntGreaterThanZero::class;
    }

    public function it_should_throw_exception_when_value_is_zero(): void
    {
        $this->expectException(IntLessOrEqualToZeroException::class);

        new $this->vo(0);
    }

    public function it_should_throw_exception_when_value_is_negative(): void
    {
        $this->expectException(IntLessOrEqualToZeroException::class);

        new $this->vo($this->faker->randomDigitNotZero() * -1);
    }
}
