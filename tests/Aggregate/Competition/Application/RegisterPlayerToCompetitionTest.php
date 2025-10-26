<?php

namespace App\Tests\Aggregate\Competition\Application;

use App\Aggregate\Player\Domain\Player;
use PHPUnit\Framework\MockObject\MockObject;
use App\Aggregate\Competition\Domain\Competition;
use App\Aggregate\Player\Application\RetrievePlayer;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Aggregate\Competition\Domain\Repository\CompetitionRepository;
use App\Tests\Aggregate\Competition\Domain\Mother\CompetitionIdMother;
use App\Aggregate\Player\Application\Exception\RetrievePlayerException;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerFederationCodeMother;
use App\Aggregate\Competition\Domain\Exception\CompetitionNotFoundException;
use App\Aggregate\Competition\Application\RegisterPlayerToCompetition\RegisterPlayerToCompetitionCommand;
use App\Aggregate\Competition\Application\RegisterPlayerToCompetition\RegisterPlayerToCompetitionHandler;
use App\Shared\Domain\Event\EventBus;

class RegisterPlayerToCompetitionTest extends UnitTestCase
{
    private readonly CompetitionRepository|MockObject $competitionRepository;
    private readonly RetrievePlayer|MockObject $retrievePlayer;
    private readonly EventBus|MockObject $eventBus;
    private readonly RegisterPlayerToCompetitionHandler $registerPlayerToCompetition;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var CompetitionRepository $competitionRepository */
        $this->competitionRepository = $this->createMock(CompetitionRepository::class);
        /** @var RetrievePlayer $retrievePlayer */
        $this->retrievePlayer = $this->createMock(RetrievePlayer::class);
        /** @var EventBus $eventBus */
        $this->eventBus = $this->createMock(EventBus::class);

        $this->registerPlayerToCompetition = new RegisterPlayerToCompetitionHandler(
            $this->competitionRepository,
            $this->retrievePlayer,
            $this->eventBus
        );
    }

    public function test_should_throw_exception_when_competition_not_found(): void
    {
        $command = new RegisterPlayerToCompetitionCommand(
            competitionId: CompetitionIdMother::create(),
            playerFederation: PlayerFederationCodeMother::create(),
        );

        $this->competitionRepository
            ->method('findById')
            ->willReturn(null);

        $this->expectException(CompetitionNotFoundException::class);

        $this->registerPlayerToCompetition->__invoke($command);
    }

    public function test_should_not_register_player_when_player_not_found_and_data_not_fetched(): void
    {
        $command = new RegisterPlayerToCompetitionCommand(
            competitionId: CompetitionIdMother::create(),
            playerFederation: PlayerFederationCodeMother::create(),
        );

        // Mockeamos la competición para luego comprobar que no se llama a registerPlayer
        $competition = $this->createMock(Competition::class);

        $this->competitionRepository
            ->method('findById')
            ->willReturn($competition);

        $this->retrievePlayer
            ->method('__invoke')
            ->willThrowException(new RetrievePlayerException());

        $this->expectException(RetrievePlayerException::class);

        // Como no se ha podido recuperar el jugador, no se debe llamar a registerPlayer
        $competition->expects($this->never())
            ->method('registerPlayer');

        $this->registerPlayerToCompetition->__invoke($command);
    }

    public function test_should_register_player_to_competition(): void
    {
        $command = new RegisterPlayerToCompetitionCommand(
            competitionId: CompetitionIdMother::create(),
            playerFederation: PlayerFederationCodeMother::create(),
        );

        // Mockeamos la competición para luego comprobar que se llama a registerPlayer
        $competition = $this->createMock(Competition::class);

        $this->competitionRepository
            ->method('findById')
            ->willReturn($competition);

        // Mockeamos el jugador que se va a recuperar
        $player = $this->createMock(Player::class);

        $this->retrievePlayer
            ->method('__invoke')
            ->willReturn($player);

        // Comprobamos que se llama a registerPlayer con el jugador recuperado
        $competition->expects($this->once())
            ->method('registerPlayer')
            ->with($player);

        $this->registerPlayerToCompetition->__invoke($command);
    }
}
