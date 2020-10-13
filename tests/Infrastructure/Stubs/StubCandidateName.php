<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\ValueObject\StringValueObject;
use Faker\Factory;

class StubCandidateName
{
    const LOCALE = 'ca_ES';

    public static function random()
    {
        $faker = Factory::create(self::LOCALE);

        return StringValueObject::fromString($faker->name);
    }
}