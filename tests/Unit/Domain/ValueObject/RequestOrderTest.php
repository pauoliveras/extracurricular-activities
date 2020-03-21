<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\RequestOrder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RequestOrderTest extends TestCase
{
    public function test_negative_values_not_allowed()
    {
        $this->expectException(InvalidArgumentException::class);

        new RequestOrder(-1);
    }

    public function test_minimum_order_value_is_one()
    {
        $this->expectException(InvalidArgumentException::class);

        new RequestOrder(0);
    }

    public function test_request_order_can_be_initialized()
    {
        $this->assertEquals(1, (RequestOrder::initial())->value());
    }

    public function test_request_order_can_be_incremented()
    {
        $initialRequestOrder = RequestOrder::initial();
        $this->assertEquals(2, ($initialRequestOrder->next())->value());
    }

    public function test_request_order_is_immutable()
    {
        $initialRequestOrder = RequestOrder::initial();
        $nextRequestOrder = $initialRequestOrder->next();

        $this->assertNotSame($initialRequestOrder, $nextRequestOrder);
    }

}