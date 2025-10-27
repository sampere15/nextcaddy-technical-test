<?php

namespace App\Tests\Aggregate\Player\Application\Sync\Mother;

use App\Shared\Domain\ValueObject\Gender;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;
use App\Aggregate\Player\Application\Sync\SyncPlayerDataHandlerDTO;
use App\Tests\Aggregate\Club\Domain\Mother\ClubCodeMother;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerFederationCodeMother;
use App\Tests\Shared\Domain\Mother\GenderMother;
use App\Tests\Shared\Domain\Util\FakerProvider;

final class SyncPlayerDataHandlerDTOMother
{
    public static function create(
        ?PlayerFederatedCode $federatedCode = null,
        ?ClubCode $clubCode = null,
        ?PlayerSurname $surname = null,
        ?PlayerFirstName $firstName = null,
        ?Gender $gender = null,
        ?PlayerBirthdate $birthdate = null,
        ?PlayerActive $active = null,
    ): SyncPlayerDataHandlerDTO {
        $faker = FakerProvider::getFaker();

        return new SyncPlayerDataHandlerDTO(
            federatedCode: $federatedCode ?? PlayerFederationCodeMother::create(),
            clubCode: $clubCode ?? ClubCodeMother::create(),
            surname: $surname ?? new PlayerSurname($faker->lastName()),
            firstName: $firstName ?? new PlayerFirstName($faker->firstName()),
            gender: $gender ?? GenderMother::create(),
            birthdate: $birthdate ?? new PlayerBirthdate(new \DateTime($faker->date())),
            active: $active ?? new PlayerActive($faker->boolean()),
        );
    }

    public static function createWithActiveStatus(bool $isActive): SyncPlayerDataHandlerDTO
    {
        return self::create(
            active: new PlayerActive($isActive)
        );
    }
}
