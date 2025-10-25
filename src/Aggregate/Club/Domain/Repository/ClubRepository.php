<?php

namespace App\Aggregate\Club\Domain\Repository;

use App\Aggregate\Club\Domain\Club;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;

interface ClubRepository
{
    public function save(Club $club): void;

    public function findById(ClubId $clubId): ?Club;

    public function findByCode(ClubCode $clubCode): ?Club;
}
