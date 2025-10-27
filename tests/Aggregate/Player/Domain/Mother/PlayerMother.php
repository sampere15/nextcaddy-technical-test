<?php

namespace App\Tests\Aggregate\Player\Domain\Mother;

use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\ValueObject\Gender;
use App\Tests\Shared\Domain\Util\FakerProvider;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederationCode;
use App\Tests\Aggregate\Club\Domain\Mother\ClubIdMother;

final class PlayerMother
{
    public static function create(
        ?PlayerId $id = null,
        ?PlayerFederationCode $federatedCode = null,
        ?ClubId $clubId = null,
        ?PlayerFirstName $name = null,
        ?PlayerSurname $surname = null,
        ?Gender $gender = null,
        ?PlayerBirthdate $birthdate = null,
        ?PlayerActive $active = null,
    ): Player {
        $faker = FakerProvider::getFaker();

        return Player::create(
            $id ?? new PlayerId($faker->uuid()),
            $clubId ?? ClubIdMother::create(),
            $federatedCode ?? new PlayerFederationCode($faker->regexify('[0-9]{6}')),
            $name ?? new PlayerFirstName($faker->firstName()),
            $surname ?? new PlayerSurname($faker->lastName()),
            $gender ?? GenderMother::create(),
            $birthdate ?? new PlayerBirthdate($faker->dateTimeBetween('-40 years', '-18 years')),
            $active ?? new PlayerActive(true)
        );
    }

    public static function activeAndFederated(
        ?ClubId $clubId = null,
    ): Player
    {
        $faker = FakerProvider::getFaker();

        return self::create(
            clubId: $clubId,
            federatedCode: PlayerFederationCodeMother::create(),
            active: new PlayerActive(true),
        );
    }

    public static function inactive(): Player 
    {
        return self::create(
            active: new PlayerActive(false),
        );
    }

    public static function withoutFederation(): Player 
    {
        $faker = FakerProvider::getFaker();

        return Player::create(
            $id ?? new PlayerId($faker->uuid()),
            $clubId ?? ClubIdMother::create(),
            PlayerFederationCodeMother::create(),
            $name ?? new PlayerFirstName($faker->firstName()),
            $surname ?? new PlayerSurname($faker->lastName()),
            $gender ?? GenderMother::create(),
            $birthdate ?? new PlayerBirthdate($faker->dateTimeBetween('-40 years', '-18 years')),
            $active ?? new PlayerActive($faker->boolean(80))
        );
    }
}
