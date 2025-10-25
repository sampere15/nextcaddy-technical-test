<?php

namespace Tests\Aggregate\Competition\Domain;

use App\Shared\Infrastructure\Uuid\UuidProvider;
use App\Aggregate\Club\Domain\ValueObject\ClubId;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerMother;
use App\Aggregate\Player\Domain\Exception\PlayerNotActiveException;
use App\Tests\Aggregate\Competition\Domain\Mother\CompetitionMother;
use App\Aggregate\Player\Domain\Exception\PlayerNotFederatedException;
use App\Aggregate\Competition\Domain\Exception\MaxPlayersExceededException;
use App\Aggregate\Competition\Domain\Exception\PlayerClubDontMatchCompetitionClubException;
use App\Aggregate\Competition\Domain\Exception\PlayerAlreadyRegisteredInCompetitionException;

final class CompetitionTest extends UnitTestCase
{
    public function test_should_throw_exception_if_player_is_not_active(): void
    {
        $competition = CompetitionMother::create();
        $inactivePlayer = PlayerMother::inactive();

        $this->expectException(PlayerNotActiveException::class);

        $competition->registerPlayer($inactivePlayer);
    }

    public function test_should_throw_exception_if_player_is_not_federated(): void
    {
        $competition = CompetitionMother::create();
        $playerWithoutCode = PlayerMother::withoutFederation();

        $this->expectException(PlayerNotFederatedException::class);

        $competition->registerPlayer($playerWithoutCode);
    }

    public function test_should_throw_exception_if_player_already_registered(): void
    {
        $competition = CompetitionMother::create();
        $player = PlayerMother::activeAndFederated(clubId: $competition->clubId());

        $competition->registerPlayer($player);

        $this->expectException(PlayerAlreadyRegisteredInCompetitionException::class);

        $competition->registerPlayer($player);
    }

    public function test_should_throw_exception_if_competition_is_full(): void
    {
        $competition = CompetitionMother::withMaxPlayers(1);
        $player1 = PlayerMother::activeAndFederated(clubId: $competition->clubId());
        $player2 = PlayerMother::activeAndFederated(clubId: $competition->clubId());

        $competition->registerPlayer($player1);

        $this->expectException(MaxPlayersExceededException::class);

        $competition->registerPlayer($player2);
    }

    public function test_should_register_active_and_federated_player(): void
    {
        $competition = CompetitionMother::create();
        $player = PlayerMother::activeAndFederated(clubId: $competition->clubId());

        $competition->registerPlayer($player);

        self::assertTrue($competition->isPlayerRegistered($player));
    }

    public function test_should_throw_exception_if_player_club_dont_match_competition_club(): void
    {
        $player = PlayerMother::create(clubId: new ClubId(UuidProvider::generate()));
        $competition = CompetitionMother::create(clubId: new ClubId(UuidProvider::generate()));

        $this->expectException(PlayerClubDontMatchCompetitionClubException::class);

        $competition->registerPlayer($player);
    }
}
