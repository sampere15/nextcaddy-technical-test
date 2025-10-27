<?php

namespace App\Aggregate\Club\Infrastructure\Persistence\Doctrine;

use App\Aggregate\Club\Domain\Club;
use Doctrine\ORM\EntityManagerInterface;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Club\Domain\Repository\ClubRepository;
use App\Aggregate\Club\Infrastructure\Persistence\Doctrine\Mapper\ClubMapper;
use App\Aggregate\Club\Infrastructure\Persistence\Doctrine\Entity\DoctrineClub;

final class ClubDoctrineRepositoryWithMapper implements ClubRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {}

    public function save(Club $club): void
    {
        $entity = ClubMapper::toEntity($club);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findById(ClubId $clubId): ?Club
    {
        $entity = $this->em->getRepository(DoctrineClub::class)->find($clubId->value());
        return $entity ? ClubMapper::toDomain($entity) : null;
    }

    public function findByCode(ClubCode $clubCode): ?Club
    {
        $entity = $this->em->getRepository(DoctrineClub::class)->findOneBy(['code' => $clubCode->value()]);
        return $entity ? ClubMapper::toDomain($entity) : null;
    }
}
