<?php

namespace Aggregate\Club\Domain;

use PHPUnit\Framework\TestCase;
use App\Aggregate\Club\Domain\Club;
use App\Tests\Aggregate\Club\Domain\Mother\ClubMother;

final class ClubTest extends TestCase
{
    public function test_it_should_create_a_random_club(): void
    {
        $club = ClubMother::create();

        $this->assertNotNull($club->id());
        $this->assertNotNull($club->code());
        $this->assertNotNull($club->name());
        $this->assertInstanceOf(Club::class, $club);
    }

    public function test_it_should_create_a_club_with_given_values(): void
    {
        $clubId = ClubMother::create()->id();
        $clubCode = ClubMother::create()->code();
        $clubName = ClubMother::create()->name();

        $club = ClubMother::create($clubId, $clubCode, $clubName);

        $this->assertEquals($clubId, $club->id());
        $this->assertEquals($clubCode, $club->code());
        $this->assertEquals($clubName, $club->name());
    }
}
