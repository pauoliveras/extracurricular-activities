<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\SequenceNumber;
use Faker\Factory;

class StubCandidateNumber
{
    public static function random()
    {
        $faker = Factory::create();

        return CandidateNumber::fromInt($faker->numberBetween(1, 50));
    }

    public static function randomGreaterThan(int $candidateNumber)
    {
        $faker = Factory::create();

        return CandidateNumber::fromInt($faker->numberBetween($candidateNumber, $candidateNumber + 50));
    }
}