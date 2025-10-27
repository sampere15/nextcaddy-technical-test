<?php

namespace App\Aggregate\Competition\Infrastructure\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Aggregate\Player\Infrastructure\Persistence\Doctrine\Entity\DoctrinePlayer;

final class DoctrineCompetition
{
    /** @var Collection|DoctrinePlayer[] */
    private Collection $players;

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $clubId,
        public readonly \DateTime $startDateTime,
        public readonly int $maxPlayers,
    ) {
        $this->players = new ArrayCollection();
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(DoctrinePlayer $player): void
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }
    }

    public function removePlayer(DoctrinePlayer $player): void
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
        }
    }
}
