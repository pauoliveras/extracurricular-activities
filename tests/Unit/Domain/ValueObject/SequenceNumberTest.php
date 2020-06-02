<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\SequenceNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SequenceNumberTest extends TestCase
{
    public function test_negative_values_not_allowed()
    {
        $this->expectException(InvalidArgumentException::class);

        new SequenceNumber(-1);
    }

    public function test_minimum_order_value_is_one()
    {
        $this->expectException(InvalidArgumentException::class);

        new SequenceNumber(0);
    }

    public function test_request_order_can_be_initialized()
    {
        $this->assertEquals(1, (SequenceNumber::initial())->value());
    }

    public function test_request_order_can_be_incremented()
    {
        $initialSequenceNumber = SequenceNumber::initial();
        $this->assertEquals(2, ($initialSequenceNumber->next())->value());
    }

    public function test_request_order_is_immutable()
    {
        $initialSequenceNumber = SequenceNumber::initial();
        $nextSequenceNumber = $initialSequenceNumber->next();

        $this->assertNotSame($initialSequenceNumber, $nextSequenceNumber);
    }

}