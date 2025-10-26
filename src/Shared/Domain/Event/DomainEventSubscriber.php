<?php

namespace App\Shared\Domain\Event;

interface DomainEventSubscriber
{
    /**
     * @return string[] Lista de eventos a los que se suscribe
     */
    public static function subscribedTo(): array;

    public function __invoke(DomainEvent $event): void;
}
