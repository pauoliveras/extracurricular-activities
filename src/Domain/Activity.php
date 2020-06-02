<?php

namespace App\Domain;

use App\Domain\Exception\DuplicateParticipantEnrollmentException;
use App\Domain\Exception\ParticipantEnrollmentClosedException;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Capacity;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\StringValueObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Activity
 * @package App\Domain
 * @ORM\Entity
 */
class Activity
{
    /**
     * @ORM\Column(type="identity")
     * @ORM\Id()
     */
    private string $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private string $code;

    /**
     * @ORM\Column(type="integer")
     */
    private int $capacity;
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Participant",
     *     mappedBy="activity",
     *     cascade={"persist", "remove", "merge"},
     *     orphanRemoval=true,
     *     fetch="EAGER"
     * )
     * @ORM\OrderBy({"candidateNumber" = "ASC"})
     *
     */
    private $participants = [];

    public function __construct(Id $id, ActivityCode $code, Capacity $capacity)
    {
        $this->id = $id;
        $this->code = $code->value();
        $this->capacity = $capacity->value();
        $this->participants = new ArrayCollection();
    }

    public static function create(Id $id, ActivityCode $activityCode, Capacity $capacity)
    {
        return new self($id, $activityCode, $capacity);
    }

    public function isNull(): bool
    {
        return false;
    }

    public function enroll(Email $email, StringValueObject $participantName, CandidateNumber $candidateNumber)
    {
        if ($this->capacity <= $this->participants->count()) {
            throw ParticipantEnrollmentClosedException::ofActivity(ActivityCode::fromString($this->code));
        }
        $participant = new Participant(Id::next(), $this, $email, $participantName, $candidateNumber);
        $this->assertParticipantNotAlreadyEnrolled($participantName);
        $this->participants->add($participant);
    }

    public function participants()
    {
        return $this->participants;
    }

    protected function assertParticipantNotAlreadyEnrolled(StringValueObject $participantName): void
    {
        if (
            $this->participants->filter(
                function (Participant $participant) use ($participantName) {
                    return $participant->name()->equals($participantName);
                }
            )->count() > 0
        ) {
            throw DuplicateParticipantEnrollmentException::ofParticipant($participantName, ActivityCode::fromString($this->code));
        }
    }

    public function code(): ActivityCode
    {
        return ActivityCode::fromString($this->code);
    }
}