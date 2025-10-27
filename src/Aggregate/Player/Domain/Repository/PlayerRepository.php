<?php

namespace App\Aggregate\Player\Domain\Repository;

use App\Aggregate\Player\Domain\Player;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;

interface PlayerRepository
{
    public function save(Player $player): void;

    public function findById(PlayerId $playerId): ?Player;

    /** @return Player[] */
    public function all(): array;

    public function findByFederationCode(PlayerFederatedCode $federationCode): ?Player;
}
