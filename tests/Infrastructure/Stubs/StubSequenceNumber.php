<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\SequenceNumber;
use Faker\Factory;

class StubSequenceNumber
{
    public static function random()
    {
        $faker = Factory::create();

        return SequenceNumber::fromInt($faker->numberBetween(1, 50));
    }

    public static function randomGreaterThan(int $sequenceNumber)
    {
        $faker = Factory::create();

        return SequenceNumber::fromInt($faker->numberBetween($sequenceNumber, $sequenceNumber + 50));
    }
}