<?php

namespace App\Aggregate\Player\Infrastructure\Persistence\Doctrine;

use App\Aggregate\Player\Domain\Player;
use Doctrine\ORM\EntityManagerInterface;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;

class PlayerDoctrineRepository implements PlayerRepository
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function save(Player $player): void
    {
        $this->em->persist($player);
        $this->em->flush();
    }

    public function findById(PlayerId $playerId): ?Player
    {
        return $this->em->getRepository(Player::class)->find($playerId->value());
    }

    /** @return Player[] */
    public function all(): array
    {
        return $this->em->getRepository(Player::class)->findAll();
    }

    public function findByFederationCode(PlayerFederatedCode $federationCode): ?Player
    {
        return $this->em->getRepository(Player::class)->findOneBy(['federationCode' => $federationCode->value()]);
    }
}
