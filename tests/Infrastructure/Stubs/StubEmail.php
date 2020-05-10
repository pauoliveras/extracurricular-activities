<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\Email;
use Faker\Factory;

class StubEmail
{
    public static function random()
    {
        $faker = Factory::create();

        return Email::fromString($faker->email);
    }
}