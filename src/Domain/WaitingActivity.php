<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\RequestOrder;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class WaitingActivity
{
    /**
     * @ORM\Column(type="identity")
     * @ORM\Id()
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $activityCode;
    /**
     * @ORM\Column(type="integer", name="requested_order")
     */
    private int $order;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\WaitingCandidate", fetch="EAGER")
     */
    private WaitingCandidate $waitingCandidate;

    private function __construct(Id $id, ActivityCode $activityCode, RequestOrder $order, WaitingCandidate $waitingCandidate)
    {

        $this->id = $id;
        $this->activityCode = $activityCode->value();
        $this->order = $order->value();
        $this->waitingCandidate = $waitingCandidate;
    }

    public static function register(Id $id, ActivityCode $activityCode, RequestOrder $order, WaitingCandidate $waitingCandidate)
    {
        return new self($id, $activityCode, $order, $waitingCandidate);
    }

}