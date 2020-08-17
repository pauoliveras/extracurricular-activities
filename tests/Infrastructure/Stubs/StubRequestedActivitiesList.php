<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\RequestedActivitiesList;
use Faker\Factory;

class StubRequestedActivitiesList
{
    private const DEFAULT_ACTIVITIES = ['ioga', 'dansa', 'circ', 'anglès', 'piscina'];

    public static function randomWith(array $activities)
    {
        $faker = Factory::create();
        return RequestedActivitiesList::createFromArray(
            $faker->randomElements($activities, random_int(1, count($activities)))
        );
    }

    public static function random()
    {
        $faker = Factory::create();
        return RequestedActivitiesList::createFromArray(
            $faker->randomElements(self::DEFAULT_ACTIVITIES, random_int(1, count(self::DEFAULT_ACTIVITIES)))
        );
    }

    public static function create(array $activities)
    {
        return RequestedActivitiesList::createFromArray($activities);
    }
}