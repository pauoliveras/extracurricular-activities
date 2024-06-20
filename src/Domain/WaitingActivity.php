<?php

namespace App\Domain;

class WaitingActivity implements \JsonSerializable
{
    private string $activityCode;
    private int $order;

    private function __construct(string $activityCode, int $order)
    {
        $this->activityCode = $activityCode;
        $this->order = $order;
    }

    public static function register(string $activityCode, int $order)
    {
        return new self($activityCode, $order);
    }

    public function jsonSerialize()
    {
        return json_encode(['activityCode' => $this->activityCode, 'order' => $this->order]);
    }
}