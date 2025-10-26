<?php

namespace App\Tests\Aggregate\Player\Application;

use App\Shared\Domain\Event\EventBus;
use PHPUnit\Framework\MockObject\MockObject;
use App\Aggregate\Player\Application\PlayerCreator;
use App\Aggregate\Club\Domain\Repository\ClubRepository;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Club\Domain\Exception\ClubNotFoundException;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Aggregate\Player\Domain\ValueObject\PlayerBirthdate;
use App\Aggregate\Player\Domain\ValueObject\PlayerFirstName;
use App\Aggregate\Player\Domain\ValueObject\PlayerSurname;
use App\Aggregate\Player\Domain\ValueObject\PlayerSynced;
use App\Tests\Aggregate\Club\Domain\Mother\ClubCodeMother;
use App\Tests\Aggregate\Club\Domain\Mother\ClubMother;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerFederationCodeMother;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerMother;
use App\Tests\Shared\Domain\Mother\GenderMother;

final class PlayerCreatorTest extends UnitTestCase
{
    private readonly PlayerRepository|MockObject $repository;
    private readonly ClubRepository|MockObject $clubRepository;
    private readonly EventBus|MockObject $eventBus;
    private readonly PlayerCreator $playerCreator;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var PlayerRepository $repository */
        $this->repository = $this->createMock(PlayerRepository::class);
        /** @var ClubRepository $clubRepository */
        $this->clubRepository = $this->createMock(ClubRepository::class);
        /** @var EventBus $eventBus */
        $this->eventBus = $this->createMock(EventBus::class);

        $this->playerCreator = new PlayerCreator(
            $this->repository,
            $this->clubRepository,
            $this->eventBus,
        );
    }

    public function test_should_throw_exception_when_club_not_found(): void
    {
        $this->clubRepository
            ->method('findByCode')
            ->willReturn(null);

        $this->expectException(ClubNotFoundException::class);

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->playerCreator->__invoke(
            ClubCodeMother::create(),
            PlayerFederationCodeMother::create(),
            new PlayerFirstName($this->faker->firstName()),
            new PlayerSurname($this->faker->lastName()),
            GenderMother::create(),
            new PlayerBirthdate($this->faker->dateTimeBetween('-40 years', '-18 years')),
            new PlayerActive($this->faker->boolean()),
            new PlayerSynced($this->faker->boolean()),
        );
    }

    public function test_it_should_create_a_player(): void
    {
        $club = ClubMother::create();
        $player = PlayerMother::create(clubId: $club->id());

        $this->clubRepository
            ->method('findByCode')
            ->willReturn($club);

        $this->repository->expects($this->once())
            ->method('save');

        $this->eventBus->expects($this->once())
            ->method('publish');

        $newPlayer = $this->playerCreator->__invoke(
            $club->code(),
            $player->federatedCode(),
            $player->name(),
            $player->surname(),
            $player->gender(),
            $player->birthdate(),
            $player->active(),
            $player->isSynced(),
        );

        $this->assertSame($club->id(), $newPlayer->clubId());
        $this->assertSame($player->federatedCode(), $newPlayer->federatedCode());
        $this->assertSame($player->name(), $newPlayer->name());
        $this->assertSame($player->surname(), $newPlayer->surname());
        $this->assertSame($player->gender(), $newPlayer->gender());
        $this->assertSame($player->birthdate(), $newPlayer->birthdate());
        $this->assertSame($player->active(), $newPlayer->active());
        $this->assertSame($player->isSynced(), $newPlayer->isSynced());
    }
}
