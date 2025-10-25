<?php

namespace App\Aggregate\Club\Domain;

use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Club\Domain\ValueObject\ClubName;

final class Club
{
    private function __construct(
        private readonly ClubId $id,
        private readonly ClubCode $clubCode,
        private readonly ClubName $name,
    ) {

    }

    public static function create(
        ClubId $id,
        ClubCode $clubCode,
        ClubName $name,
    ): self {
        return new self($id, $clubCode, $name);
    }

    public function id(): ClubId
    {
        return $this->id;
    }

    public function code(): ClubCode
    {
        return $this->clubCode;
    }

    public function name(): ClubName
    {
        return $this->name;
    }
}
