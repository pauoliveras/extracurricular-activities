<?php

namespace App\Domain\ValueObject;

class RequestOrder
{
    private $order;

    public function __construct(int $order)
    {
        $this->order = $order;
    }

    public static function fromInt(int $order)
    {
        return new self($order);
    }

    public function value()
    {
        return $this->order;
    }
}