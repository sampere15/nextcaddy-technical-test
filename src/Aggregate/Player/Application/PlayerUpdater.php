<?php

namespace App\Aggregate\Player\Application;

use App\Shared\Domain\Event\EventBus;
use App\Aggregate\Player\Domain\Player;
use App\Aggregate\Player\Domain\DTO\PlayerUpdaterDTO;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Club\Domain\Repository\ClubRepository;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Club\Domain\Exception\ClubNotFoundException;
use App\Aggregate\Player\Application\Exception\PlayerNotFoundException;

class PlayerUpdater
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly ClubRepository $clubRepository,
        private readonly EventBus $eventBus,
    ) {}

    public function __invoke(
        PlayerId $playerId,
        PlayerUpdaterDTO $dto,
    ): Player {
        // Recuperamos el jugador a actualizar y el club que se ha indicado en el dto
        $player = $this->playerRepository->findById($playerId) ?? throw new PlayerNotFoundException($playerId);
        $newClub = $this->clubRepository->findByCode($dto->clubCode) ?? throw new ClubNotFoundException($dto->clubCode);

        $player->update(
            $newClub->id(),
            $dto->active,
        );

        $this->eventBus->publish(...$player->pullDomainEvents());

        return $player;
    }
}
