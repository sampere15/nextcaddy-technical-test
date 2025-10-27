<?php
namespace App\Aggregate\Player\Infrastructure\Event;

use App\Aggregate\Competition\Domain\Repository\CompetitionRepository;
use App\Aggregate\Player\Domain\Event\PlayerClubChanged;
use App\Shared\Domain\Event\DomainEventSubscriber;
use App\Shared\Domain\Event\EventBus;

class CheckPlayerCompetitionsRegisteredAfterUpdateClub implements DomainEventSubscriber
{
    public static function subscribedTo(): array
    {
        return [
            PlayerClubChanged::class,
        ];
    }

    public function __construct(
        private readonly CompetitionRepository $competitionRepository,
        private readonly EventBus $eventBus,
    ) {}

    public function __invoke(PlayerClubChanged $event): void
    {
        $playerId = $event->playerId();
        $newClubId = $event->newClubId();

        $competitions = $this->competitionRepository->findPlayerCompetitions($playerId);

        foreach ($competitions as $competition) {
            // Si la competición ya no se celebra en el club del jugador, se desinscribe al jugador
            if (!$competition->clubId()->value() !== $newClubId->value()) {
                $competition->unregisterPlayer($playerId);
            }

            // Guardamos los cambios en la competición
            $this->competitionRepository->save($competition);

            // Publicamos los eventos generados por la competición
            $this->eventBus->publish(...$competition->pullDomainEvents());
        }
    }
}
