<?php

namespace App\Aggregate\Player\Application\Sync;

use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;
use App\Aggregate\Player\Application\Sync\SyncPlayerDataHandlerDTO;

interface PlayerSyncContract
{
    public function fetchPlayerData(PlayerFederatedCode $federationCode): SyncPlayerDataHandlerDTO;

    /**
     * Aquí podrían añadirse los otros métodos para atacar a las otros endpoints disponibles
     */
}
