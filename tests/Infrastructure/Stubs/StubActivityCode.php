<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\ActivityCode;
use Faker\Factory;

class StubActivityCode
{
    public static function random()
    {
        $faker = Factory::create();

        return ActivityCode::fromString($faker->word);
    }
}