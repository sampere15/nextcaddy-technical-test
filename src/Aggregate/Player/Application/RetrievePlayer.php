<?php

namespace App\Aggregate\Player\Application;

use App\Aggregate\Player\Domain\Player;
use App\Aggregate\Player\Domain\DTO\PlayerUpdaterDTO;
use App\Aggregate\Player\Domain\ValueObject\PlayerSynced;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Player\Application\Sync\PlayerSyncContract;
use App\Aggregate\Player\Application\Exception\RetrievePlayerException;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;

/**
 * Caso de uso que orquesta la recuperación de un Player desde la federación, actualiza los datos si es necesario
 * y lo devuelve.
 */
class RetrievePlayer
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly PlayerSyncContract $playerSyncContract,
        private readonly PlayerCreator $playerCreator,
        private readonly PlayerUpdater $playerUpdater,
    ) {}

    public function __invoke(PlayerFederatedCode $code): Player 
    {
        $federationData = null;

        // Busca el jugador en el repositorio local
        $player = $this->playerRepository->findByFederationCode($code);

        try {
            // Recupera los datos del jugador desde la federación
            $federationData = $this->playerSyncContract->fetchPlayerData($code);
        } catch (\Throwable $th) {
            // Indicamos que el juegador no se ha podido sincronizar con los datos de la federación
            if (null !== $player) {
                $player->markAsUnsynced();
                $this->playerRepository->save($player);
            }
        }

        // Si no existe en local y no hemos podido recuperar datos de la federación, lanzamos excepción
        if (null === $player && null === $federationData) {
            throw new RetrievePlayerException("Player with federation code {$code->value()} not found.");
        }

        if (null === $player && null !== $federationData) {
            // Si no existe y hemos recuparado datos de la federación, creamos el player
            $player = $this->playerCreator->__invoke(
                $federationData->clubCode,
                $federationData->federatedCode,
                $federationData->firstName,
                $federationData->surname,
                $federationData->gender,
                $federationData->birthdate,
                $federationData->active,
                new PlayerSynced(true)
            );
        } elseif (null !== $player && null !== $federationData) {
            // Si existe y hemos recuperado datos de la federación, llamamos al updater para actualizar si es necesario
            $player = $this->playerUpdater->__invoke(
                $player->id(),
                new PlayerUpdaterDTO(
                    clubCode: $federationData->clubCode,
                    active: $federationData->active,
                )
            );
        }

        return $player;
    }
}
