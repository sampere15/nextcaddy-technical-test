<?php

namespace Aggregate\Player\Domain\ValueObject;

use App\Aggregate\Player\Domain\Exception\InvalidPlayerFederationCodeException;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederationCode;
use App\Tests\Shared\Domain\ValueObject\StringValueObjectTest;

final class PlayerFederationCodeTest extends StringValueObjectTest
{
    protected function getValidValue(): string
    {
        return '123456';
    }

    protected function getValueObjectClass(): string
    {
        return PlayerFederationCode::class;
    }

    public function test_it_should_throw_exception_when_format_is_invalid1(): void
    {
        $this->expectException(InvalidPlayerFederationCodeException::class);

        new PlayerFederationCode('ABC123');
    }

    public function test_it_should_throw_exception_when_length_is_invalid1(): void
    {
        $this->expectException(InvalidPlayerFederationCodeException::class);

        new PlayerFederationCode(str_repeat('A', PlayerFederationCode::TAM + 1));
    }

    public function test_it_should_throw_exception_when_length_is_invalid2(): void
    {
        $this->expectException(InvalidPlayerFederationCodeException::class);

        new PlayerFederationCode(str_repeat('A', PlayerFederationCode::TAM - 1));
    }

    public function test_creates_a_valid_player_federation_code(): void
    {
        $code = '123456';
        $federationCode = new PlayerFederationCode($code);

        $this->assertEquals($code, $federationCode->value());
    }
}
