<?php

namespace App\Aggregate\Competition\Domain;

use App\Aggregate\Player\Domain\Player;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionName;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionMaxPlayers;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionStartDateTime;
use App\Aggregate\Competition\Domain\Exception\PlayerAlreadyRegisteredInCompetitionException;

final class Competition
{
    /** @var Player[] $players */
    private array $players = [];

    private function __construct(
        private readonly CompetitionId $id,
        private readonly CompetitionName $name,
        private readonly ClubId $clubId,
        private readonly CompetitionStartDateTime $startDateTime,
        private readonly CompetitionMaxPlayers $maxPlayers,
    ) {}

    public static function create(
        CompetitionId $id,
        CompetitionName $name,
        ClubId $clubId,
        CompetitionStartDateTime $startDateTime,
        CompetitionMaxPlayers $maxPlayers,
    ): self {
        return new self(
            $id,
            $name,
            $clubId,
            $startDateTime,
            $maxPlayers,
        );
    }

    /** @return Player[] */
    public function players(): array
    {
        return $this->players;
    }

    public function addPlayer(Player $player): void
    {
        $this->checkPlayerNotRegistered($player);

        $this->players[] = $player;
    }

    private function checkPlayerNotRegistered(Player $player): void
    {
        foreach ($this->players as $registeredPlayer) {
            if ($registeredPlayer->federatedCode()->value() === $player->federatedCode()->value()) {
                throw new PlayerAlreadyRegisteredInCompetitionException($player);
            }
        }
    }
}
