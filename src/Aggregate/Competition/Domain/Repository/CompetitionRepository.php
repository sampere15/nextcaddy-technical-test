<?php
namespace App\Aggregate\Competition\Domain\Repository;

use App\Aggregate\Competition\Domain\Competition;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;

interface CompetitionRepository
{
    public function save(Competition $competition): void;

    public function findById(CompetitionId $competitionId): ?Competition;

    /** @return Competition[] */
    public function findPlayerCompetitions(PlayerId $playerId): array;
}
