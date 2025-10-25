<?php
namespace App\Aggregate\Competition\Domain\Repository;

use App\Aggregate\Competition\Domain\Competition;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;

interface CompetitionRepository
{
    public function save(Competition $competition): void;

    public function findById(CompetitionId $competitionId): ?Competition;
}
