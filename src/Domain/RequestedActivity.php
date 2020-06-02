<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\RequestOrder;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class RequestedActivity
{
    /**
     * @ORM\Column(type="identity")
     * @ORM\Id()
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $activityCode;
    /**
     * @ORM\Column(type="integer", name="requested_order")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Candidate", fetch="EAGER")
     */
    private $candidate;

    /**
     * RequestedActivty constructor.
     * @param $activityCode
     * @param $order
     */
    public function __construct(Id $id, ActivityCode $activityCode, RequestOrder $order, Candidate $candidate)
    {
        $this->id = $id;
        $this->activityCode = $activityCode->value();
        $this->order = $order->value();
        $this->candidate = $candidate;
    }

    public function code(): ActivityCode
    {
        return ActivityCode::fromString($this->activityCode);
    }

    public function order()
    {
        return RequestOrder::fromInt($this->order);
    }

}