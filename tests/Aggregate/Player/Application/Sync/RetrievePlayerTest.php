<?php

namespace App\Tests\Aggregate\Player\Application\Sync;

use PHPUnit\Framework\MockObject\MockObject;
use App\Aggregate\Player\Application\RetrievePlayer;
use App\Tests\Aggregate\Club\Domain\Mother\ClubMother;
use App\Aggregate\Club\Domain\Repository\ClubRepository;
use App\Aggregate\Player\Domain\ValueObject\PlayerActive;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Aggregate\Club\Domain\Mother\ClubCodeMother;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerMother;
use App\Aggregate\Player\Domain\Repository\PlayerRepository;
use App\Aggregate\Player\Application\Sync\PlayerSyncContract;
use App\Aggregate\Club\Domain\Exception\ClubNotFoundException;
use App\Aggregate\Player\Application\Exception\RetrievePlayerException;
use App\Tests\Aggregate\Player\Application\Sync\Mother\SyncPlayerDataHandlerDTOMother;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerFederationCodeMother;

class RetrievePlayerTest extends UnitTestCase
{
    private readonly PlayerRepository|MockObject $playerRepository;
    private readonly PlayerSyncContract|MockObject $playerSyncContract;
    private readonly ClubRepository|MockObject $clubRepository;
    private readonly RetrievePlayer $retrievePlayerUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var PlayerRepository $playerRepository */
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        /** @var PlayerSyncContract $playerSyncContract */
        $this->playerSyncContract = $this->createMock(PlayerSyncContract::class);
        /** @var ClubRepository $clubRepository */
        $this->clubRepository = $this->createMock(ClubRepository::class);

        $this->retrievePlayerUseCase = new RetrievePlayer(
            $this->playerRepository,
            $this->playerSyncContract,
            $this->clubRepository,
        );
    }

    public function test_should_create_new_player_when_not_found_and_data_fetched(): void
    {
        $this->playerRepository
            ->method('findByFederationCode')
            ->willReturn(null);

        $fetchedData = SyncPlayerDataHandlerDTOMother::create();
        $club = ClubMother::create();

        // Mockeamos la llamada al servicio de sincronización para que devuelva datos
        $this->playerSyncContract
            ->method('fetchPlayerData')
            ->willReturn($fetchedData);

        // Indicamos que sí que va a encontrar el club asociado según el código devuelto por la federación
        $this->clubRepository
            ->method('findByCode')
            ->with($fetchedData->clubCode)
            ->willReturn($club);

        // Se debe llamar al método save para guardar el nuevo jugador
        $this->playerRepository
            ->expects($this->once())
            ->method('save');

        $player = $this->retrievePlayerUseCase->__invoke($fetchedData->federatedCode);

        // Comprobamos que el jugador devuelto tiene los datos correctos
        $this->assertEquals($fetchedData->federatedCode->value(), $player->federatedCode()->value());
        $this->assertEquals($club->id()->value(), $player->clubId()->value());
        $this->assertEquals($fetchedData->firstName->value(), $player->name()->value());
        $this->assertEquals($fetchedData->surname->value(), $player->surname()->value());
        $this->assertEquals($fetchedData->gender->value(), $player->gender()->value());
        $this->assertEquals($fetchedData->birthdate->value()->format('Y-m-d'), $player->birthdate()->value()->format('Y-m-d'));
        $this->assertEquals($fetchedData->active->value(), $player->active()->value());
    }

    public function test_it_should_update_existing_player_when_data_fetched(): void
    {
        $player = PlayerMother::activeAndFederated();

        $this->playerRepository
            ->expects($this->once())
            ->method('findByFederationCode')
            ->with($player->federatedCode()->value())
            ->willReturn($player);

        $newClub = ClubCodeMother::create();
        $newClub = ClubMother::create();
        $fetchedData = SyncPlayerDataHandlerDTOMother::create(
            clubCode: $newClub->code(),
            active: new PlayerActive(false),
        );

        // Mockeamos la llamada al servicio de sincronización para que devuelva datos
        $this->playerSyncContract
            ->expects($this->once())
            ->method('fetchPlayerData')
            ->willReturn($fetchedData);

        // Indicamos que sí que va a encontrar el club al que pertence el usuario
        $currentClub = ClubMother::create();
        $this->clubRepository
            ->expects($this->once())
            ->method('findById')
            ->with($player->clubId())
            ->willReturn($currentClub);

        // Indicamos que sí que va a encontrar el nuevo club según el código devuelto por la federación
        $this->clubRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($newClub->code())
            ->willReturn($newClub);

        $this->playerRepository
            ->expects($this->once())
            ->method('save');

        $player = $this->retrievePlayerUseCase->__invoke($player->federatedCode());

        // Comprobamos que ha cambiado el código del club y el estado activo
        $this->assertEquals($fetchedData->clubCode->value(), $newClub->code()->value());
        $this->assertEquals($fetchedData->active->value(), $player->active()->value());
    }

    public function test_it_should_not_update_player_and_register_to_competition_when_data_fetched(): void
    {
        // Indicamos que sí que va a recuperar el jugador del repositorio
        $player = PlayerMother::activeAndFederated();
        $initialClubCode = $player->clubId();
        $initialActiveStatus = $player->active();

        $this->playerRepository
            ->expects($this->once())
            ->method('findByFederationCode')
            ->willReturn($player);

        $playerCurrentyClub = ClubMother::create(id: $player->clubId());
        $fetchedData = SyncPlayerDataHandlerDTOMother::create(
            clubCode: $playerCurrentyClub->code(),
            active: $player->active(),
        );

        // Mockeamos la llamada al servicio de sincronización para que devuelva datos
        $this->playerSyncContract
            ->expects($this->once())
            ->method('fetchPlayerData')
            ->willReturn($fetchedData);

        // Indicamos que sí que va a encontrar el club asociado según el código devuelto por la federación
        $this->clubRepository
            ->expects($this->once())
            ->method('findById')
            ->with($player->clubId())
            ->willReturn($playerCurrentyClub);

        $this->playerRepository
            ->expects($this->never())
            ->method('save');

        $player = $this->retrievePlayerUseCase->__invoke($player->federatedCode());

        // Comprobamos que el jugador tiene los mismos datos
        $this->assertEquals($fetchedData->clubCode->value(), $playerCurrentyClub->code()->value());
        $this->assertEquals($fetchedData->active->value(), $player->active()->value());
        $this->assertEquals($initialClubCode->value(), $player->clubId()->value());
        $this->assertEquals($initialActiveStatus->value(), $player->active()->value());
    }

    public function test_should_throw_exception_when_player_fetched_data_fetched_and_current_player_club_not_found(): void
    {
        $player = PlayerMother::activeAndFederated();

        $this->playerRepository
            ->method('findByFederationCode')
            ->with($player->federatedCode()->value())
            ->willReturn($player);

        $fetchedData = SyncPlayerDataHandlerDTOMother::create();

        // Mockeamos la llamada al servicio de sincronización para que devuelva datos
        $this->playerSyncContract
            ->method('fetchPlayerData')
            ->willReturn($fetchedData);

        // Indicamos que no va a encontrar el club actual del jugador
        $this->clubRepository
            ->method('findById')
            ->with($player->clubId())
            ->willReturn(null);

        $this->expectException(ClubNotFoundException::class);

        $this->playerRepository
            ->expects($this->never())
            ->method('save');

        $this->retrievePlayerUseCase->__invoke($player->federatedCode());
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

        $player = $this->retrievePlayerUseCase->__invoke($player->federatedCode());

        $this->assertFalse($player->isSynced());
    }
}
