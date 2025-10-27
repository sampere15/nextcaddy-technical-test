<?php

namespace App\Aggregate\Competition\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Aggregate\Competition\Domain\Competition;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Competition\Domain\Repository\CompetitionRepository;
use App\Aggregate\Competition\Infrastructure\Persistence\Doctrine\Entity\DoctrineCompetition;
use App\Aggregate\Competition\Infrastructure\Persistence\Doctrine\Mapper\CompetitionMapper;

class CompetitionDoctrineRepository implements CompetitionRepository
{
    private const ENTITY_CLASS = DoctrineCompetition::class;

    public function __construct(private readonly EntityManagerInterface $em) {}

    public function save(Competition $competition): void
    {
        $entity = $this->em->getRepository(self::ENTITY_CLASS)->find($competition->id()->value());

        if (!$entity) {
            $entity = CompetitionMapper::toEntity($competition);
            $this->em->persist($entity);
        }
        
        $this->em->flush();
    }

    public function findById(CompetitionId $competitionId): ?Competition
    {
        $entity = $this->em->getRepository(self::ENTITY_CLASS)->find($competitionId->value());
        return $entity ? CompetitionMapper::toDomain($entity) : null;
    }
}
