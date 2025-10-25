<?php

namespace App\Tests\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\Exception\EmptyStringException;
use App\Tests\Shared\Domain\AbstractClassValueObjectTest;

abstract class StringValueObjectTest extends AbstractClassValueObjectTest
{
    abstract protected function getValidValue(): string;

    protected function getValueObjectClass(): string
    {
        return StringValueObject::class;
    }

    public function test_it_should_thorww_exception_when_string_is_empty(): void
    {
        $this->expectException(EmptyStringException::class);

        new $this->vo('');
    }

    public function test_it_should_trim_string(): void
    {
        $validValue = $this->getValidValue();
        $valueObject = new $this->vo('  ' . $validValue . '  ');

        $this->assertEquals($validValue, $valueObject->value());
    }
}
