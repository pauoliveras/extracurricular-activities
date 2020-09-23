<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\DesiredActivityCount;
use Faker\Factory;

class StubDesiredActivityCount
{
    public static function random()
    {
        $faker = Factory::create();

        return DesiredActivityCount::fromInt($faker->numberBetween(1, DesiredActivityCount::MAXIMUM_DESIRED_ACTIVITIES));
    }
}