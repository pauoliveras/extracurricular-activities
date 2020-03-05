<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\RequestOrder;

class RequestedActivty
{
    private $activityCode;
    private $order;

    /**
     * RequestedActivty constructor.
     * @param $activityCode
     * @param $order
     */
    public function __construct(ActivityCode $activityCode, RequestOrder $order)
    {
        $this->activityCode = $activityCode->value();
        $this->order = $order->value();
    }


}