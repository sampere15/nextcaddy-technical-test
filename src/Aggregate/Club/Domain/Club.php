<?php

namespace App\Aggregate\Club\Domain;

use App\Aggregate\Player\Domain\Player;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Club\Domain\ValueObject\ClubName;
use App\Shared\Domain\Event\AggregateRoot;

final class Club extends AggregateRoot
{
    /** @var Player[] $players */
    private array $players = [];
    /** @var Competition[] $competitions */
    private array $competitions = [];

    private function __construct(
        private readonly ClubId $id,
        private readonly ClubCode $clubCode,
        private readonly ClubName $name,
    ) {
        $this->players = [];
        $this->competitions = [];
    }

    public static function create(
        ClubId $id,
        ClubCode $clubCode,
        ClubName $name,
    ): self {
        return new self($id, $clubCode, $name);
    }

    public function id(): ClubId
    {
        return $this->id;
    }

    public function code(): ClubCode
    {
        return $this->clubCode;
    }

    public function name(): ClubName
    {
        return $this->name;
    }

    /** @return Player[] */
    public function players(): array
    {
        return $this->players;
    }

    /** @return Competition[] */
    public function competitions(): array
    {
        return $this->competitions;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->value(),
            'club_code' => $this->clubCode->value(),
            'name' => $this->name->value(),
        ];
    }
}

