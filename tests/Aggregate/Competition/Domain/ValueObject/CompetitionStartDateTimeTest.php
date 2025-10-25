<?php
namespace App\Tests\Aggregate\Competition\Domain\ValueObject;

use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use App\Aggregate\Competition\Domain\Exception\CannotBePastDateException;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionStartDateTime;

final class CompetitionStartDateTimeTest extends UnitTestCase
{
    public function test_it_should_throw_exception_when_date_is_in_the_past(): void
    {
        $pastDate = new \DateTime('-1 day');
        
        $this->expectException(CannotBePastDateException::class);

        new CompetitionStartDateTime($pastDate);
    }

    public function test_it_should_create_competition_start_date_time_when_date_is_in_the_future(): void
    {
        $futureDate = new \DateTime('+1 day');

        $competitionStartDateTime = new CompetitionStartDateTime($futureDate);

        $this->assertInstanceOf(CompetitionStartDateTime::class, $competitionStartDateTime);
        $this->assertEquals($futureDate->format('Y-m-d H:i:s'), (string)$competitionStartDateTime);
    }
}
