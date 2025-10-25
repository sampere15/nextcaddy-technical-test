<?php

namespace App\Aggregate\Player\Application;

use App\Aggregate\Player\Domain\Player;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Club\Domain\Repository\ClubRepository;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Player\Application\Sync\PlayerSyncContract;
use App\Aggregate\Club\Domain\Exception\ClubNotFoundException;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederationCode;
use App\Aggregate\Player\Application\Exception\RetrievePlayerException;
use App\Shared\Infrastructure\Uuid\UuidProvider;

/**
 * Caso de uso que orquesta la recuperación de un Player desde la federación, actualiza los datos si es necesario
 * y lo devuelve.
 */
class RetrievePlayer
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly PlayerSyncContract $playerSyncContract,
        private readonly ClubRepository $clubRepository,
    ) {}

    public function __invoke(PlayerFederationCode $code): Player 
    {
        $federationData = null;
        $hasChanges = false;

        // Busca el jugador en el repositorio local
        $player = $this->playerRepository->findByFederationCode($code);

        try {
            // Recupera los datos del jugador desde la federación
            $federationData = $this->playerSyncContract->fetchPlayerData($code);
        } catch (\Throwable $th) {
            // Indicamos que el juegador no se ha podido sincronizar con los datos de la federación
            if (null !== $player) {
                $player->markAsUnsynced();
                $hasChanges = true;
            }
        }

        if (null === $player && null !== $federationData) {
            // Si no existe y hemos recuparado datos de la federación, lo creamos
            // Buscamos el club asociado al jugador
            $club = $this->clubRepository->findByCode(new ClubCode($federationData->clubCode)) ?? throw new ClubNotFoundException(new ClubCode($federationData->clubCode));

            $player = Player::create(
                new PlayerId(UuidProvider::generate()),
                $federationData->federatedCode,
                $club->id(),
                $federationData->firstName,
                $federationData->surname,
                $federationData->gender,
                $federationData->birthdate,
                $federationData->active,
            );

            $hasChanges = true;
        } elseif (null !== $player && null !== $federationData) {
            // Si existe, actualizamos sus datos si han cambiado
            $hasChanges = false;
            // Recuperamos el club actual del jugador para poder comprobar si ha cambiado
            $playerCurrentyClub = $this->clubRepository->findById($player->clubId()) ?? throw new ClubNotFoundException($player->clubId());

            // Si ha cambiado el status
            if ($player->active()->value() !== $federationData->active->value()) {
                $player->updateActiveStatus($federationData->active);
                $hasChanges = true;
            }

            // Si ha cambiado el club
            if ($playerCurrentyClub->code()->value() !== $federationData->clubCode->value()) {
                // Buscamos el nuevo club por código y actualizamos
                $newClub = $this->clubRepository->findByCode(new ClubCode($federationData->clubCode)) ?? throw new ClubNotFoundException($player->clubId());
                $player->updateClubId($newClub->id());
                $hasChanges = true;
            }
        } elseif (null === $player && null === $federationData) {
            throw new RetrievePlayerException("Player with federation code {$code->value()} not found.");
        }

        if ($hasChanges) {
            $this->playerRepository->save($player);
        }

        // TODO: quizás guardar el usuario en cache

        return $player;
    }
}
