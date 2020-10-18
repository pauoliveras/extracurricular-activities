<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\RequestOrder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Class Candidate
 * @package App\Domain
 * @ORM\Entity()
 */
class WaitingCandidate
{
    /**
     * @ORM\Column(type="identity")
     * @ORM\Id()
     */
    private string $id;
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\WaitingActivity",
     *     mappedBy="waitingCandidate",
     *     cascade={"persist", "remove", "merge"},
     *     orphanRemoval=true,
     *     fetch="EAGER"
     * )
     * @ORM\OrderBy({"order" = "ASC"})
     *
     */
    private $waitingActivities;

    public function __construct(
        Id $id,
        WaitingActivityList $waitingActivityList
    )
    {
        $this->id = $id;
        $this->guardAgainstEmptyWaitingActivities($waitingActivityList);
        $this->waitingActivities = new ArrayCollection();

        foreach ($waitingActivityList as $requestedActivity) {
            $this->addActivity($requestedActivity);
        }
    }

    protected function guardAgainstEmptyWaitingActivities(WaitingActivityList $waitingActivityList): void
    {
        if ($waitingActivityList->isEmpty()) {
            throw new InvalidArgumentException('Waiting activities must have at least one element');
        }
    }

    public function addActivity(ActivityCode $activityCode)
    {
        $this->waitingActivities->add(
            WaitingActivity::register(
                Id::next(),
                $activityCode,
                RequestOrder::fromInt($this->waitingActivities->count() + 1),
                $this
            )
        );
    }

    public static function register(Id $candidateId, ValueObject\ActivityCode $activityCode)
    {
        return new self($candidateId, WaitingActivityList::createFromArray([$activityCode->value()]));
    }

    public function id(): Id
    {
        return Id::fromString($this->id);
    }

    protected function addWaitingActivities(WaitingActivityList $waitingActivityList): void
    {
        foreach ($waitingActivityList as $waitingActivityCode) {
            $this->addActivity($waitingActivityCode);
        }
    }
}