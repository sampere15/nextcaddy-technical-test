<?php

namespace App\Aggregate\Stats\Infrastructure\Event;

use App\Aggregate\Competition\Domain\Event\CompetitionPlayerRegistered;
use App\Aggregate\Stats\Application\IncreaseNumberCompetitorRegistered;
use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\DomainEventSubscriber;

class UpdateRegistrationsOnPlayerRegistered implements DomainEventSubscriber
{
    public static function subscribedTo(): array
    {
        return [CompetitionPlayerRegistered::class];
    }

    public function __construct(private readonly IncreaseNumberCompetitorRegistered $increase) {}

    public function __invoke(DomainEvent $event): void
    {
        $this->increase->__invoke();
    }
}
