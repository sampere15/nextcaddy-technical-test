<?php

namespace App\Tests\Aggregate\Player\Application\Sync;

use PHPUnit\Framework\MockObject\MockObject;
use App\Aggregate\Player\Application\PlayerCreator;
use App\Aggregate\Player\Application\PlayerUpdater;
use App\Aggregate\Player\Application\RetrievePlayer;
use App\Aggregate\Player\Domain\DTO\PlayerUpdaterDTO;
use App\Tests\Aggregate\Club\Domain\Mother\ClubMother;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Aggregate\Club\Domain\Mother\ClubCodeMother;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerMother;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Player\Application\Sync\PlayerSyncContract;
use App\Aggregate\Club\Domain\Exception\ClubNotFoundException;
use App\Aggregate\Player\Application\Exception\RetrievePlayerException;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerFederationCodeMother;
use App\Tests\Aggregate\Player\Application\Sync\Mother\SyncPlayerDataHandlerDTOMother;

class RetrievePlayerTest extends UnitTestCase
{
    private readonly PlayerRepository|MockObject $playerRepository;
    private readonly PlayerSyncContract|MockObject $playerSyncContract;
    private readonly PlayerCreator|MockObject $playerCreator;
    private readonly PlayerUpdater|MockObject $playerUpdater;
    private readonly RetrievePlayer $retrievePlayerUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var PlayerRepository $playerRepository */
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        /** @var PlayerSyncContract $playerSyncContract */
        $this->playerSyncContract = $this->createMock(PlayerSyncContract::class);
        /** @var PlayerCreator $playerCreator */
        $this->playerCreator = $this->createMock(PlayerCreator::class);
        /** @var PlayerUpdater $playerUpdater */
        $this->playerUpdater = $this->createMock(PlayerUpdater::class);

        $this->retrievePlayerUseCase = new RetrievePlayer(
            $this->playerRepository,
            $this->playerSyncContract,
            $this->playerCreator,
            $this->playerUpdater,
        );
    }

    public function test_should_create_new_player_when_not_found_and_data_fetched(): void
    {
        $fetchedData = SyncPlayerDataHandlerDTOMother::create();
        $player = PlayerMother::create(
            clubId: null,
            federatedCode: $fetchedData->federatedCode,
            name: $fetchedData->firstName,
            surname: $fetchedData->surname,
            gender: $fetchedData->gender,
            birthdate: $fetchedData->birthdate,
            active: $fetchedData->active,
        );
        
        $this->playerRepository
            ->method('findByFederationCode')
            ->willReturn(null);

        $this->playerSyncContract
            ->method('fetchPlayerData')
            ->willReturn($fetchedData);

        $this->playerCreator->expects($this->once())
            ->method('__invoke')
            ->with(
                $fetchedData->clubCode,
                $fetchedData->federatedCode,
                $fetchedData->firstName,
                $fetchedData->surname,
                $fetchedData->gender,
                $fetchedData->birthdate,
                $fetchedData->active
            )->willReturn($player);

        $player = $this->retrievePlayerUseCase->__invoke($fetchedData->federatedCode);
    }

    public function test_it_should_update_existing_player_when_data_fetched(): void
    {
        $player = PlayerMother::activeAndFederated();

        $this->playerRepository
            ->expects($this->once())
            ->method('findByFederationCode')
            ->with($player->federatedCode()->value())
            ->willReturn($player);

        $newClub = ClubMother::create();
        $fetchedData = SyncPlayerDataHandlerDTOMother::create(
            clubCode: $newClub->code(),
            active: new PlayerActive(false),
        );

        $this->playerSyncContract
            ->expects($this->once())
            ->method('fetchPlayerData')
            ->willReturn($fetchedData);

        $this->playerUpdater
            ->expects($this->once())
            ->method('__invoke')
            ->with(
                $player->id(),
                new PlayerUpdaterDTO(
                    clubCode: $fetchedData->clubCode,
                    active: $fetchedData->active,
                )
            );

        $player = $this->retrievePlayerUseCase->__invoke($player->federatedCode());
    }

    public function test_it_should_throw_exception_when_player_not_found_and_data_not_fetched(): void
    {
        $this->playerRepository
            ->method('findByFederationCode')
            ->willReturn(null);

        // Mockeamos la llamada al servicio de sincronización para que lance una excepción
        $this->playerSyncContract
            ->method('fetchPlayerData')
            ->willThrowException(new \Exception());

        $this->expectException(RetrievePlayerException::class);

        $this->retrievePlayerUseCase->__invoke(PlayerFederationCodeMother::create());
    }

    public function test_get_player_from_repository_cannot_fetch_data_marks_as_unsynced(): void
    {
        // Indicamos que sí que va a recuperar el jugador del repositorio
        $player = PlayerMother::activeAndFederated();
        $this->playerRepository
            ->method('findByFederationCode')
            ->with($player->federatedCode()->value())
            ->willReturn($player);

        // No se puede recuperar la info de la federación
        $this->playerSyncContract
            ->method('fetchPlayerData')
            ->willThrowException(new \Exception());

        $this->playerRepository
            ->expects($this->once())
            ->method('save');

        $this->retrievePlayerUseCase->__invoke($player->federatedCode());

        $this->assertFalse($player->isSynced()->value());
    }
}
