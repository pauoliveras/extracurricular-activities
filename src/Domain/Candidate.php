<?php

namespace App\Domain;

use App\Domain\ValueObject\BooleanValueObject;
use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\DesiredActivityCount;
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="integer",name="candidate_number", nullable=true)
     */
    private $candidateNumber;
    /**
     * @ORM\OneToMany(
     *     targetEntity="RequestedActivity",
     *     mappedBy="candidate",
     *     cascade={"persist", "remove", "merge"},
     *     orphanRemoval=true,
     *     fetch="EAGER"
     * )
     * @ORM\OrderBy({"order" = "ASC"})
     *
     */
    private $requestedActivities = [];
    /**
     * @ORM\Column(type="string",name="candidate_code", unique=true)
     */
    private string $code;
    /**
     * @ORM\Column(type="integer",name="desired_activity_count", nullable=false)
     */
    private int $desiredActivityCount;
    /**
     * @ORM\Column(type="boolean",name="membership", nullable=false, options={"default":false})
     */
    private bool $membership;

    public function __construct(
        Id $id,
        CandidateCode $code,
        Email $email,
        StringValueObject $candidateName,
        StringValueObject $group,
        RequestedActivitiesList $requestedActivities,
        DesiredActivityCount $desiredActivityCount,
        BooleanValueObject $membership
    )
    {
        $this->guardAgainstEmptyRequestedActivities($requestedActivities);
        $this->id = $id;
        $this->code = $code->value();
        $this->email = $email->value();
        $this->candidateName = $candidateName->value();
        $this->group = $group->value();
        $this->requestedActivities = new ArrayCollection();
        $this->addRequestedActivities($requestedActivities);
        $this->desiredActivityCount = $desiredActivityCount->value();
        $this->membership = $membership->value();
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
                new RequestedActivity(
                    Id::next(),
                    $requestedActivityCode,
                    $requestOrder,
                    $this
                )
            );
            $requestOrder->next();
        }
    }

    public function candidateCode(): CandidateCode
    {
        return CandidateCode::fromString($this->code);
    }

    public function email(): Email
    {
        return Email::fromString($this->email);
    }

    public function requestedActivities(): ArrayCollection
    {
        $iterator = new ArrayIterator($this->requestedActivities->toArray());

        $iterator->uasort(
            function (RequestedActivity $activityA, RequestedActivity $activityB) {
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

    public function number(): ?CandidateNumber
    {
        return $this->candidateNumber ? CandidateNumber::fromInt($this->candidateNumber) : null;
    }

    public function assignNumber(CandidateNumber $number)
    {
        $this->candidateNumber = $number->value();
    }

    public function desiredActivityCount(): DesiredActivityCount
    {
        return DesiredActivityCount::fromInt($this->desiredActivityCount);
    }

    public function isMember(): bool
    {
        return $this->membership()->isTrue();
    }

    private function membership()
    {
        return BooleanValueObject::fromValue($this->membership);
    }
}