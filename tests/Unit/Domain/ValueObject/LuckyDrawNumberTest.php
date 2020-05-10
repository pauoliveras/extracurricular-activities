<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\LuckyDrawNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LuckyDrawNumberTest extends TestCase
{
    public function test_negative_values_not_allowed()
    {
        $this->expectException(InvalidArgumentException::class);

        new LuckyDrawNumber(-1);
    }

    public function test_minimum_order_value_is_one()
    {
        $this->expectException(InvalidArgumentException::class);

        new LuckyDrawNumber(0);
    }
}