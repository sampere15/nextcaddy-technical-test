<?php

namespace App\Aggregate\Competition\Domain;

use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\Event\AggregateRoot;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionName;
use App\Aggregate\Player\Domain\Exception\PlayerNotActiveException;
use App\Aggregate\Player\Domain\Exception\PlayerNotFederatedException;
use App\Aggregate\Competition\Domain\Event\CompetitionPlayerRegistered;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionMaxPlayers;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionStartDateTime;
use App\Aggregate\Competition\Domain\Exception\MaxPlayersExceededException;
use App\Aggregate\Competition\Domain\Exception\PlayerClubDontMatchCompetitionClubException;
use App\Aggregate\Competition\Domain\Exception\PlayerAlreadyRegisteredInCompetitionException;

class Competition extends AggregateRoot
{
    /** @var Player[] $players */
    private array $players = [];

    private function __construct(
        private readonly CompetitionId $id,
        private readonly CompetitionName $name,
        private readonly ClubId $clubId,
        private readonly CompetitionStartDateTime $startDateTime,
        private readonly CompetitionMaxPlayers $maxPlayers,
        array $players = [],
    ) {
        $this->players = $players;
    }

    public static function create(
        CompetitionId $id,
        CompetitionName $name,
        ClubId $clubId,
        CompetitionStartDateTime $startDateTime,
        CompetitionMaxPlayers $maxPlayers,
        array $players = [],
    ): self {
        return new self(
            $id,
            $name,
            $clubId,
            $startDateTime,
            $maxPlayers,
            $players
        );
    }

    /** @return Player[] */
    public function players(): array
    {
        return $this->players;
    }

    public function registerPlayer(Player $player): void
    {
        // Player must be active
        if (false === $player->active()->value()) {
            throw new PlayerNotActiveException($player);
        }

        // Player must have a federated code
        if (null === $player->federatedCode()) {
            throw new PlayerNotFederatedException($player);
        }

        // Check if player is already registered
        if ($this->isPlayerRegistered($player)) {
            throw new PlayerAlreadyRegisteredInCompetitionException($player);
        }

        // Check player's club matches competition's club
        if ($player->clubId()->value() !== $this->clubId()->value()) {
            throw new PlayerClubDontMatchCompetitionClubException($player, $this);
        }

        // Check if competition is full
        $this->checkIfCompetitionIsFull();

        $this->players[] = $player;

        $this->record(new CompetitionPlayerRegistered($this->id, $player->id()));
    }

    public function isPlayerRegistered(Player $player): bool
    {
        foreach ($this->players as $registeredPlayer) {
            if ($registeredPlayer->federatedCode()->value() === $player->federatedCode()->value()) {
                return true;
            }
        }

        return false;
    }

    public function numberOfRegisteredPlayers(): int
    {
        return count($this->players);
    }

    private function checkIfCompetitionIsFull(): void
    {
        if ($this->numberOfRegisteredPlayers() >= $this->maxPlayers->value()) {
            throw new MaxPlayersExceededException($this->maxPlayers->value());
        }
    }

    public function id(): CompetitionId
    {
        return $this->id;
    }

    public function clubId(): ClubId
    {
        return $this->clubId;
    }

    public function jsonSerialize(bool $includePlayers = false): array
    {
        $data = [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'club_id' => $this->clubId->value(),
            'start_datetime' => $this->startDateTime->value()->format('Y-m-d H:i:s'),
            'max_players' => $this->maxPlayers->value(),
        ];

        if ($includePlayers) {
            $data['players'] = array_map(
                fn (Player $player) => $player->jsonSerialize(),
                $this->players
            );
        }

        return $data;
    }
}
