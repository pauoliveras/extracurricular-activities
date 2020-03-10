<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Candidate
 * @package App\Domain
 * @ORM\Entity()
 */
class Candidate
{
    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private $id;
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string")
     */
    private $candidateName;
    /**
     * @ORM\Column(type="string",name="candidate_group")
     */
    private $group;
    /**
     * @ORM\OneToMany(targetEntity="App\Domain\RequestedActivty", mappedBy="candidate", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $requestedActivities;

    public function __construct(
        Id $id,
        Email $email,
        StringValueObject $candidateName,
        StringValueObject $group,
        array $requestedActivities
    )
    {
        $this->id = $id;
        $this->email = $email->value();
        $this->candidateName = $candidateName->value();
        $this->group = $group->value();
        foreach ($requestedActivities as $requestedActivity) {
            $this->addRequestedActivity($requestedActivity[0], $requestedActivity[1]);
        }
    }

    private function addRequestedActivity(ActivityCode $activityCode, RequestOrder $order)
    {
        $this->requestedActivities[] = new RequestedActivty(Id::next(), $activityCode, $order, $this);
    }

    public function email(): Email
    {
        return Email::fromString($this->email);
    }

    public function requestedActivities(): ArrayCollection
    {
        return new ArrayCollection($this->requestedActivities->toArray());
    }

    public function isNull()
    {
        return false;
    }
}