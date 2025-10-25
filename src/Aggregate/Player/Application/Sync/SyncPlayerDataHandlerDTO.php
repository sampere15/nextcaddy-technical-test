<?php

namespace App\Aggregate\Player\Application\Sync;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederationCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Shared\Domain\ValueObject\Gender;

final class SyncPlayerDataHandlerDTO
{
    public function __construct(
        public readonly PlayerFederationCode $federatedCode,
        public readonly ClubCode $clubCode,
        public readonly PlayerSurname $surname,
        public readonly PlayerFirstName $firstName,
        public readonly Gender $gender,
        public readonly PlayerBirthdate $birthdate,
        public readonly PlayerActive $active,
    ) {}
}
