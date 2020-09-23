<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\DesiredActivityCount;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DesiredActivityCountTest extends TestCase
{
    public function test_negative_values_not_allowed()
    {
        $this->expectException(InvalidArgumentException::class);

        DesiredActivityCount::fromInt(-1);
    }

    public function test_minimum_order_value_is_one()
    {
        $this->expectException(InvalidArgumentException::class);

        DesiredActivityCount::fromInt(0);
    }

    public function test_values_over_maximum_are_not_allowed()
    {
        $this->expectException(InvalidArgumentException::class);

        DesiredActivityCount::fromInt(DesiredActivityCount::MAXIMUM_DESIRED_ACTIVITIES + 1);
    }

    public function test_max_desired_activities_is_set_by_default()
    {
        $desiredActivityCount = DesiredActivityCount::fromInt(null);

        $this->assertEquals(DesiredActivityCount::MAXIMUM_DESIRED_ACTIVITIES, $desiredActivityCount->value());
    }
}