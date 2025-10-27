<?php

namespace App\Aggregate\Competition\Infrastructure\Persistence\Doctrine\Mapper;

use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Aggregate\Competition\Domain\Competition;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionId;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionName;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionMaxPlayers;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionStartDateTime;
use App\Aggregate\Player\Infrastructure\Persistence\Doctrine\Mapper\PlayerMapper;
use App\Aggregate\Competition\Infrastructure\Persistence\Doctrine\Entity\DoctrineCompetition;

final class CompetitionMapper
{
    public static function toDomain(DoctrineCompetition $entity): Competition
    {
        $players = [];
        foreach ($entity->getPlayers() as $playerEntity) {
            $players[] = PlayerMapper::toDomain($playerEntity);
        }
        
        $competition = Competition::create(
            new CompetitionId($entity->id),
            new CompetitionName($entity->name),
            new ClubId($entity->clubId),
            new CompetitionStartDateTime($entity->startDateTime),
            new CompetitionMaxPlayers($entity->maxPlayers),
            $players,
        );

        // Vaciamos los posibles eventos que se hayan podido generar al crear el agregado
        $competition->pullDomainEvents();

        return $competition;
    }

    public static function toEntity(Competition $competition): DoctrineCompetition
    {
        $entity = new DoctrineCompetition(
            $competition->id()->value(),
            $competition->name()->value(),
            $competition->clubId()->value(),
            $competition->startDateTime()->value(),
            $competition->maxPlayers()->value(),
        );

        return $entity;
    }
}
