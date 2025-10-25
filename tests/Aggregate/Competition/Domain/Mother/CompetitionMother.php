<?php

namespace App\Tests\Aggregate\Competition\Domain\Mother;

use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Competition\Domain\Competition;
use App\Tests\Aggregate\Club\Domain\Mother\ClubIdMother;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionName;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionMaxPlayers;
use App\Tests\Aggregate\Competition\Domain\Mother\CompetitionNameMother;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionStartDateTime;

class CompetitionMother
{
    public static function create(
        ?CompetitionId $id = null,
        ?CompetitionName $name = null,
        ?ClubId $clubId = null,
        ?CompetitionStartDateTime $startDateTime = null,
        ?CompetitionMaxPlayers $maxPlayers = null,
    ): Competition {
        return Competition::create(
            $id ?? CompetitionIdMother::create(),
            $name ?? CompetitionNameMother::create(),
            $clubId ?? ClubIdMother::create(),
            $startDateTime ?? CompetitionStartDateTimeMother::create(),
            $maxPlayers ?? new CompetitionMaxPlayers(random_int(10, 400)),
        );
    }

    public static function withMaxPlayers(int $maxPlayers): Competition {
        return self::create(
            null,
            null,
            null,
            null,
            new CompetitionMaxPlayers($maxPlayers)
        );
    }
}
