<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\RequestOrder;
use App\Domain\ValueObject\StringValueObject;
use ArrayIterator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Class Candidate
 * @package App\Domain
 * @ORM\Entity()
 */
class Candidate
{
    /**
     * @ORM\Column(type="identity")
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
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\RequestedActivty",
     *     mappedBy="candidate",
     *     cascade={"persist", "remove", "merge"},
     *     orphanRemoval=true,
     *     fetch="EAGER"
     * )
     * @ORM\OrderBy({"order" = "ASC"})
     *
     */
    private $requestedActivities = [];

    public function __construct(
        Id $id,
        Email $email,
        StringValueObject $candidateName,
        StringValueObject $group,
        RequestedActivitiesList $requestedActivities
    )
    {
        $this->guardAgainstEmptyRequestedActivities($requestedActivities);
        $this->id = $id;
        $this->email = $email->value();
        $this->candidateName = $candidateName->value();
        $this->group = $group->value();
        $this->requestedActivities = new ArrayCollection();
        $this->addRequestedActivities($requestedActivities);
    }

    protected function guardAgainstEmptyRequestedActivities(RequestedActivitiesList $requestedActivities): void
    {
        if ($requestedActivities->isEmpty()) {
            throw new InvalidArgumentException('Requested activities must have at least one element');
        }
    }

    protected function addRequestedActivities(RequestedActivitiesList $requestedActivities): void
    {
        $requestOrder = RequestOrder::initial();

        foreach ($requestedActivities as $requestedActivityCode) {
            $this->requestedActivities->add(
                new RequestedActivty(
                    Id::next(),
                    $requestedActivityCode,
                    $requestOrder,
                    $this
                )
            );
            $requestOrder->next();
        }
    }

    private function addRequestedActivity(ActivityCode $activityCode)
    {
    }

    public function email(): Email
    {
        return Email::fromString($this->email);
    }

    public function requestedActivities(): ArrayCollection
    {
        $iterator = new ArrayIterator($this->requestedActivities->toArray());

        $iterator->uasort(
            function (RequestedActivty $activityA, RequestedActivty $activityB) {
                return $activityA->order()->value() > $activityB->order()->value() ? 1 : -1;
            }
        );

        return new ArrayCollection(iterator_to_array($iterator));
    }

    public function isNull()
    {
        return false;
    }

    public function candidateName(): StringValueObject
    {
        return StringValueObject::fromString($this->candidateName);
    }

    public function candidateGroup(): StringValueObject
    {
        return StringValueObject::fromString($this->group);
    }

}