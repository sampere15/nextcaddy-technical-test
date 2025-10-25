<?php

namespace App\Tests\Aggregate\Competition\Domain\ValueObject;

use App\Tests\Shared\Domain\AbstractClassValueObjectTest;
use App\Aggregate\Competition\Domain\ValueObject\CompetitionName;

final class CompetitionNameTest extends AbstractClassValueObjectTest
{
    protected function getValueObjectClass(): string
    {
        return CompetitionName::class;
    }

    protected function validValue(): mixed
    {
        return 'Champions League';
    }

    public function test_it_should_create_a_competition_name(): void
    {
        $name = new CompetitionName('Premier League');

        $this->assertInstanceOf(CompetitionName::class, $name);
        $this->assertEquals('Premier League', $name->value());
    }
}
