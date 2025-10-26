<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;
use JsonSerializable;

/**
 * AggregateRoot abstrae el registro de eventos de dominios en cualquier agregado.
 */
abstract class AggregateRoot implements JsonSerializable
{
    private array $domainEvents = [];

    /**
     * Obtiene los eventos de dominio y reseta la lisat de eventos para no enviar dos eventos iguales.
     */
    final public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    /**
     * Registra un evento para ser enviado posteriormente.
     */
    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    abstract public function jsonSerialize(): array;
}
