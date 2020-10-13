<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\BooleanValueObject;
use Faker\Factory;

class StubBooleanValueObject
{
    public static function random(): BooleanValueObject
    {
        $faker = Factory::create();

        return BooleanValueObject::fromValue($faker->boolean);
    }
}