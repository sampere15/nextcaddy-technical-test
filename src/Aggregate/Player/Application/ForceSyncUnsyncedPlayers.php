<?php

namespace App\Aggregate\Player\Application;

use App\Aggregate\Player\Domain\Repository\PlayerRepository;

/**
 * Se puede dar el caso en el que se haya permitido inscribirse un jugador a un torneo sin que se hayan podido recuperar
 * sus datos. Que un jugador cambio de club no suele ser lo más habitual
 */
final class ForceSyncUnsyncedPlayers
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly RetrievePlayer $retrievePlayer,
    ) {}

    public function __invoke(): void
    {
        $unsyncedPlayers = $this->playerRepository->findAllUnsyncedPlayers();

        foreach ($unsyncedPlayers as $player) {
            try {
                $this->retrievePlayer->__invoke($player->federatedCode());
            } catch (\Throwable) {
                // Si no se puede sincronizar, no hacemos nada
            }
        }
    }
}