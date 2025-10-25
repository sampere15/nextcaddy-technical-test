<?php

namespace App\Aggregate\Competition\Application\RegisterPlayerToCompetition;

use App\Aggregate\Competition\Domain\Exception\CompetitionNotFoundException;
use App\Aggregate\Competition\Domain\Repository\CompetitionRepository;
use App\Aggregate\Player\Application\RetrievePlayer;

final class RegisterPlayerToCompetitionHandler
{
    public function __construct(
        private readonly CompetitionRepository $competitionRepository,
        private readonly RetrievePlayer $retrievePlayer,
    ) {}

    public function __invoke(RegisterPlayerToCompetitionCommand $command): void
    {
        $competitionId = $command->competitionId;

        // Fetch competition
        $competition = $this->competitionRepository->findById($competitionId)
            ?? throw new CompetitionNotFoundException($competitionId);

        // Fetch player
        $player = $this->retrievePlayer->__invoke($command->playerFederation);

        $competition->registerPlayer($player);

        $this->competitionRepository->save($competition);

        // TODO: Registrar/emitir evento
    }
}
