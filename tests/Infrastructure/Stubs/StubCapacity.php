<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\Capacity;
use Faker\Factory;

class StubCapacity
{
    public static function random()
    {
        $faker = Factory::create();

        return Capacity::fromInt($faker->numberBetween(1, 50));
    }
}