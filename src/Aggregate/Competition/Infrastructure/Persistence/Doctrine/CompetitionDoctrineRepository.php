<?php
namespace App\Aggregate\Competition\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use App\Aggregate\Competition\Domain\Competition;
use App\Aggregate\Player\Domain\ValueObject\PlayerId;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Competition\Domain\Repository\CompetitionRepository;

class CompetitionDoctrineRepository implements CompetitionRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(Competition $competition): void
    {
        $this->em->persist($competition);
        $this->em->flush();
    }

    public function findById(CompetitionId $competitionId): ?Competition
    {
        return $this->em->getRepository(Competition::class)->find($competitionId->value());
    }

    /**
     * @return Competition[]
     */
    public function findPlayerCompetitions(PlayerId $playerId): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('c')
            ->from(Competition::class, 'c')
            ->innerJoin('c.registeredPlayers', 'rp')
            ->where('rp.playerId = :playerId')
            ->setParameter('playerId', $playerId->value());

        return $qb->getQuery()->getResult();
    }
}
