<?php

namespace App\Aggregate\Player\Infrastructure\Persistence\Doctrine;

use App\Aggregate\Player\Domain\Player;
use Doctrine\ORM\EntityManagerInterface;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Player\Domain\ValueObject\PlayerFederatedCode;
use App\Aggregate\Player\Infrastructure\Persistence\Doctrine\Entity\DoctrinePlayer;
use App\Aggregate\Player\Infrastructure\Persistence\Doctrine\Mapper\PlayerMapper;

class PlayerDoctrineRepositoryWithMapper implements PlayerRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(Player $player): void
    {
        $entity = PlayerMapper::toEntity($player);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findById(PlayerId $playerId): ?Player
    {
        $entity = $this->em->getRepository(DoctrinePlayer::class)->find($playerId->value());
        return $entity ? PlayerMapper::toDomain($entity) : null;
    }

    /** @return Player[] */
    public function all(): array
    {
        $entities = $this->em->getRepository(Player::class)->findAll();
        return array_map(fn($entity) => PlayerMapper::toDomain($entity), $entities);
    }

    public function findByFederationCode(PlayerFederatedCode $federationCode): ?Player
    {
        $entity = $this->em->getRepository(Player::class)->findOneBy(['federationCode' => $federationCode->value()]);
        return $entity ? PlayerMapper::toDomain($entity) : null;
    }
}