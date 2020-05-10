<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\StringValueObject;
use Faker\Factory;

class StubCandidateName
{
    public static function random()
    {
        $faker = Factory::create();

        return StringValueObject::fromString($faker->name);
    }
}