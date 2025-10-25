<?php

use App\Shared\Domain\ValueObject\Gender;
use App\Shared\Domain\Exception\GenderEnumException;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;

class GenderTest extends UnitTestCase
{
    public function test_it_should_throw_exception_when_invalid_gender(): void
    {
        $this->expectException(GenderEnumException::class);

        Gender::fromString('invalid_gender');
    }

    public function test_it_should_create_male_gender(): void
    {
        $gender = Gender::fromString('male');
        $this->assertEquals(Gender::MALE, $gender);
    }

    public function test_it_should_create_female_gender(): void
    {
        $gender = Gender::fromString('female');
        $this->assertEquals(Gender::FEMALE, $gender);
    }

    public function test_it_should_be_case_insensitive(): void
    {
        $gender = Gender::fromString('MaLe');
        $this->assertEquals(Gender::MALE, $gender);
        $gender = Gender::fromString('FeMaLe');
        $this->assertEquals(Gender::FEMALE, $gender);
    }

    public function test_it_should_throw_exception_if_empty_string(): void
    {
        $this->expectException(GenderEnumException::class);
        Gender::fromString('');
    }
}
