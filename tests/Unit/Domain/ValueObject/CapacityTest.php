<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Capacity;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CapacityTest extends TestCase
{
    public function test_negative_values_not_allowed()
    {
        $this->expectException(InvalidArgumentException::class);

        new Capacity(-1);
    }

    public function test_minimum_order_value_is_one()
    {
        $this->expectException(InvalidArgumentException::class);

        new Capacity(0);
    }
}