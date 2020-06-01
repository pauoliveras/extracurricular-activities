<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\StringValueObject;
use Faker\Factory;

class StubParticipantName
{
    public static function random()
    {
        $faker = Factory::create();

        return StringValueObject::fromString($faker->name);
    }
}