<?php

namespace App\Aggregate\Player\Application\Sync;

use App\Aggregate\Player\Domain\ValueObject\PlayerFederationCode;
use App\Aggregate\Player\Application\Sync\SyncPlayerDataHandlerDTO;

interface PlayerSyncContract
{
    public function fetchPlayerData(PlayerFederationCode $federationCode): SyncPlayerDataHandlerDTO;
}
