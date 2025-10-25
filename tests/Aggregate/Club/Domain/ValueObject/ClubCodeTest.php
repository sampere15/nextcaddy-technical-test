<?php
namespace Aggregate\Club\Domain\ValueObject;

use App\Aggregate\Club\Domain\Exception\ClubCodeFormatException;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Tests\Shared\Domain\ValueObject\StringValueObjectTest;

final class ClubCodeTest extends StringValueObjectTest
{
    protected function getValueObjectClass(): string
    {
        return ClubCode::class;
    }

    public function test_it_should_throw_exception_when_format_is_invalid(): void
    {
        $this->expectException(ClubCodeFormatException::class);

        new ClubCode('A1B2');
    }

    public function test_it_should_throw_exception_when_length_is_invalid1(): void
    {
        $this->expectException(ClubCodeFormatException::class);

        new ClubCode('AB123');
    }

    public function test_it_should_throw_exception_when_length_is_invalid2(): void
    {
        $this->expectException(ClubCodeFormatException::class);

        new ClubCode('AB1');
    }

    public function test_creates_a_valid_club_code(): void
    {
        $code = 'AM02';
        $clubCode = new ClubCode($code);

        $this->assertEquals($code, $clubCode->value());
    }
}
