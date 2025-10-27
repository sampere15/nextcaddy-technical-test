<?php
namespace App\Aggregate\Player\Application;

use App\Aggregate\Player\Domain\Player;
use App\Shared\Domain\ValueObject\Gender;
use App\Shared\Infrastructure\Uuid\UuidProvider;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Club\Domain\Repository\ClubRepository;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Aggregate\Player\Domain\ValueObject\PlayerSynced;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Club\Domain\Exception\ClubNotFoundException;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;
use App\Shared\Domain\Event\EventBus;

class PlayerCreator
{
    public function __construct(
        private readonly PlayerRepository $repository,
        private readonly ClubRepository $clubRepository,
        private readonly EventBus $eventBus,
    )
    {
    }

    public function __invoke(
        ClubCode $clubCode,
        PlayerFederatedCode $federatedCode,
        PlayerFirstName $firstName,
        PlayerSurname $surname,
        Gender $gender,
        PlayerBirthdate $birthdate,
        PlayerActive $active,
        PlayerSynced $synced = new PlayerSynced(true),
    ): Player
    {
        // Buscamos el club asociado al jugador
        $club = $this->clubRepository->findByCode($clubCode) ?? throw new ClubNotFoundException($clubCode);

        // Creamos el jugador y lo guardamos en el repository
        $player = Player::create(
            new PlayerId(UuidProvider::generate()),
            $club->id(),
            $federatedCode,
            $firstName,
            $surname,
            $gender,
            $birthdate,
            $active,
            $synced,
        );

        $this->repository->save($player);

        // Publicamos los eventos de dominio que se hayan podido generar
        $this->eventBus->publish(...$player->pullDomainEvents());

        return $player;
    }
}
