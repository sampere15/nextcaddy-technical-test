<?php

namespace App\Aggregate\Player\Application\ListPlayers;

use App\Aggregate\Player\Domain\Repository\PlayerRepository;

class ListPlayers
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
    ) {}

    /**
     * @return PlayerListItemResponse[]
     */
    public function __invoke(): array
    {
        $players = $this->playerRepository->findAll();

        return array_map(fn($player) => new PlayerListItemResponse(
            id: $player->id()->value(),
            federatedCode: $player->federatedCode()->value(),
            name: $player->name()->value(),
            surname: $player->surname()->value(),
            gender: $player->gender()->value(),
            birthdate: $player->birthdate()->value()->format('Y-m-d'),
        ), $players);
    }
}
