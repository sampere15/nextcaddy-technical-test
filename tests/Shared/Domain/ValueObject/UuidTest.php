<?php

namespace App\Tests\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\Exception\UuidInvalidException;
use App\Tests\Shared\Domain\AbstractClassValueObjectTest;

abstract class UuidTest extends AbstractClassValueObjectTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected abstract function getValueObjectClass(): string;

    public function test_should_throw_exception_when_company_id_is_invalid(): void
    {
        $this->expectException(UuidInvalidException::class);

        new $this->vo('invalid-uuid');
    }

    public function test_should_throw_exception_when_company_id_is_empty(): void
    {
        $this->expectException(UuidInvalidException::class);

        new $this->vo('');
    }

    public function test_should_throw_exception_when_company_id_is_larger_than_max(): void
    {
        $this->expectException(UuidInvalidException::class);

        new $this->vo(str_repeat('a', Uuid::TAM + 1));
    }

    public function test_should_throw_exception_when_company_id_is_shorter_than_max(): void
    {
        $this->expectException(UuidInvalidException::class);

        new $this->vo(str_repeat('a', Uuid::TAM - 1));
    }

    public function test_should_create_company_id(): void
    {
        $uuid = new $this->vo($this->faker->uuid());

        $this->assertInstanceOf($this->vo, $uuid);
    }
}
