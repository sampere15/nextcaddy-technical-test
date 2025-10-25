<?php

namespace App\Aggregate\Player\Domain;

use App\Shared\Domain\ValueObject\Gender;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederationCode;

final class Player
{
    private function __construct(
        private readonly PlayerId $id,
        private readonly ?PlayerFederationCode $federatedCode,
        private ClubId $clubId,
        private readonly PlayerFirstName $name,
        private readonly PlayerSurname $surname,
        private readonly Gender $gender,
        private readonly PlayerBirthdate $birthdate,
        private PlayerActive $active,
    ) {
    }

    public static function create(
        PlayerId $id,
        ?PlayerFederationCode $federatedCode,
        ClubId $clubId,
        PlayerFirstName $name,
        PlayerSurname $surname,
        Gender $gender,
        PlayerBirthdate $birthdate,
        PlayerActive $active,
    ): self {
        return new self(
            $id,
            $federatedCode,
            $clubId,
            $name,
            $surname,
            $gender,
            $birthdate,
            $active,
        );
    }

    public function id(): PlayerId
    {
        return $this->id;
    }

    public function federatedCode(): ?PlayerFederationCode
    {
        return $this->federatedCode;
    }

    public function clubId(): ClubId
    {
        return $this->clubId;
    }

    public function name(): PlayerFirstName
    {
        return $this->name;
    }

    public function surname(): PlayerSurname
    {
        return $this->surname;
    }

    public function gender(): Gender
    {
        return $this->gender;
    }

    public function birthdate(): PlayerBirthdate
    {
        return $this->birthdate;
    }

    public function active(): PlayerActive
    {
        return $this->active;
    }
}
