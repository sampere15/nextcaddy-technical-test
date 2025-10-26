<?php

use App\Shared\Domain\Event\EventBus;
use PHPUnit\Framework\MockObject\MockObject;
use App\Aggregate\Club\Domain\ValueObject\ClubCode;
use App\Aggregate\Player\Application\PlayerUpdater;
use App\Aggregate\Player\Domain\DTO\PlayerUpdaterDTO;
use App\Tests\Aggregate\Club\Domain\Mother\ClubMother;
use App\Aggregate\Club\Domain\Repository\ClubRepository;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Aggregate\Club\Domain\Mother\ClubCodeMother;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerMother;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerIdMother;
use App\Aggregate\Club\Domain\Exception\ClubNotFoundException;
use App\Aggregate\Player\Application\Exception\PlayerNotFoundException;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;

class PlayerUpdaterTest extends UnitTestCase
{
    private readonly PlayerRepository|MockObject $playerRepository;
    private readonly ClubRepository|MockObject $clubRepository;
    private readonly EventBus|MockObject $eventBus;
    private readonly PlayerUpdater $playerUpdater;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var PlayerRepository|MockObject $playerRepository */
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        /** @var ClubRepository|MockObject $clubRepository */
        $this->clubRepository = $this->createMock(ClubRepository::class);
        /** @var EventBus|MockObject $eventBus */
        $this->eventBus = $this->createMock(EventBus::class);

        $this->playerUpdater = new PlayerUpdater(
            $this->playerRepository,
            $this->clubRepository,
            $this->eventBus,
        );
    }

    public function test_it_should_throw_player_not_found_exception(): void
    {
        $playerId = PlayerIdMother::create();
        $dto = new PlayerUpdaterDTO();

        $this->playerRepository
            ->expects($this->once())
            ->method('findById')
            ->with($playerId)
            ->willReturn(null);

        $this->expectException(PlayerNotFoundException::class);

        $this->playerUpdater->__invoke($playerId, $dto);
    }

    public function test_it_should_throw_club_not_found_exception(): void
    {
        $club = ClubMother::create();
        $player = PlayerMother::create(clubId: $club->id());
        
        $dto = new PlayerUpdaterDTO(
            clubCode: ClubCodeMother::create()
        );

        $this->playerRepository
            ->expects($this->once())
            ->method('findById')
            ->with($player->id())
            ->willReturn($player);

        $this->clubRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($dto->clubCode)
            ->willReturn(null);

        $this->expectException(ClubNotFoundException::class);

        $this->playerUpdater->__invoke($player->id(), $dto);
    }

    public function test_it_should_update_player_successfully(): void
    {
        $oldClub = ClubMother::create();
        $newClub = ClubMother::create();
        $player = PlayerMother::create( 
            clubId: $oldClub->id(),
            active: new PlayerActive(false),
        );

        $this->assertFalse($player->active()->value());

        $dto = new PlayerUpdaterDTO(
            clubCode: $newClub->code(),
            active: new PlayerActive(true),
        );

        $this->playerRepository
            ->expects($this->once())
            ->method('findById')
            ->with($player->id())
            ->willReturn($player);

        $this->clubRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($dto->clubCode)
            ->willReturn($newClub);

        $this->eventBus
            ->expects($this->once())
            ->method('publish');

        $this->playerUpdater->__invoke($player->id(), $dto);

        $this->assertEquals($newClub->id(), $player->clubId());
        $this->assertTrue($player->active()->value());
        $this->assertCount(0, $player->pullDomainEvents());
    }
}
