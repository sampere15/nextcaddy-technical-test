<?php

namespace App\Tests\Aggregate\Club\Domain\Mother;

use App\Aggregate\Club\Domain\Club;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Club\Domain\ValueObject\ClubName;
use App\Tests\Aggregate\Club\Domain\Mother\ClubIdMother;
use App\Tests\Aggregate\Club\Domain\Mother\ClubCodeMother;
use App\Tests\Aggregate\Club\Domain\Mother\ClubNameMother;

final class ClubMother
{
    public static function create(
        ?ClubId $id = null,
        ?ClubCode $code = null,
        ?ClubName $name = null
    ): Club {
        return Club::create(
            $id ?? ClubIdMother::create(),
            $code ?? ClubCodeMother::create(),
            $name ?? ClubNameMother::create()
        );
    }
}
