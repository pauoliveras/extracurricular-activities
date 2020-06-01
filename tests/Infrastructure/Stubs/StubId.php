<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\Id;
use Faker\Factory;

class StubId
{
    public static function random()
    {
        $faker = Factory::create();

        return Id::fromString($faker->uuid);
    }
}