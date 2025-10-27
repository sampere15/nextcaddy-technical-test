<?php

namespace App\Aggregate\Club\Infrastructure\Persistence\Doctrine;

use App\Aggregate\Club\Domain\Club;
use Doctrine\ORM\EntityManagerInterface;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Club\Domain\Repository\ClubRepository;

class ClubDoctrineRepository implements ClubRepository
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function save(Club $club): void
    {
        $this->em->persist($club);
        $this->em->flush();
    }

    public function findById(ClubId $clubId): ?Club
    {
        return $this->em->getRepository(Club::class)->find($clubId->value());
    }

    public function findByCode(ClubCode $clubCode): ?Club
    {
        return $this->em->getRepository(Club::class)->findOneBy(['code' => $clubCode->value()]);
    }
}
