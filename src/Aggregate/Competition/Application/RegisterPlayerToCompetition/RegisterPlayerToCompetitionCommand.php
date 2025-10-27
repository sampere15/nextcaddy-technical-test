<?php

namespace App\Aggregate\Competition\Application\RegisterPlayerToCompetition;

use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;

final class RegisterPlayerToCompetitionCommand
{
    public function __construct(
        public readonly CompetitionId $competitionId,
        public readonly PlayerFederatedCode $playerFederation,
    ) {
    }
}
