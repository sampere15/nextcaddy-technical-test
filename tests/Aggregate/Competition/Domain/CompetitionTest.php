<?php

namespace Tests\Aggregate\Competition\Domain;

use App\Tests\Aggregate\Club\Domain\Mother\ClubMother;
use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Tests\Aggregate\Player\Domain\Mother\PlayerMother;
use App\Aggregate\Player\Domain\Exception\PlayerNotActiveException;
use App\Tests\Aggregate\Competition\Domain\Mother\CompetitionMother;
use App\Aggregate\Competition\Domain\Event\CompetitionPlayerRegistered;
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

        $this->assertTrue($competition->isPlayerRegistered($player));
        $this->assertCount(1, $competition->players());
        $this->assertInstanceOf(CompetitionPlayerRegistered::class, $competition->pullDomainEvents()[0]);
    }

    public function test_should_throw_exception_if_player_club_dont_match_competition_club(): void
    {
        $club1 = ClubMother::create();
        $club2 = ClubMother::create();
        $player = PlayerMother::create(clubId: $club1->id());
        $competition = CompetitionMother::create(clubId: $club2->id());

        $this->expectException(PlayerClubDontMatchCompetitionClubException::class);

        $competition->registerPlayer($player);
    }
}
