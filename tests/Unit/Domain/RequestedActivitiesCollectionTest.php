<?php

namespace App\Tests\Unit\Domain;

use App\Domain\RequestedActivitiesList;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RequestedActivitiesCollectionTest extends TestCase
{
    public function test_requested_activities_in_collection_are_unique()
    {
        $this->expectException(InvalidArgumentException::class);

        RequestedActivitiesList::createFromArray(['activity_1', 'activity_1']);
    }

    public function test_all_requested_activities_are_stored()
    {
        $activities = ['activity_1', 'activity_2', 'activity_3'];

        $requestedActivities = RequestedActivitiesList::createFromArray($activities);

        $this->assertCount(3, $requestedActivities);
    }

    /**
     * @dataProvider requestedActivities
     */
    public function test_requested_activities_are_stored_in_order($activities)
    {
        $requestedActivities = RequestedActivitiesList::createFromArray($activities);

        $this->assertEquals($activities, $requestedActivities->toArray());
    }

    public function requestedActivities(): Generator
    {
        yield 'Set 1' => [['activity_1', 'activity_2', 'activity_3']];
        yield 'Set 2' => [['activity_2', 'activity_3', 'activity_1']];
        yield 'Set 3' => [['activity_3', 'activity_1', 'activity_2']];
    }
}