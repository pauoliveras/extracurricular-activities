<?php

namespace App\Domain;

use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\SequenceNumber;
use App\Domain\ValueObject\StringValueObject;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Participant
{
    /**
     * @ORM\Column(type="identity")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $email;
    /**
     * @ORM\Column(type="string")
     */
    private string $participantName;
    /**
     * @ORM\Column(type="integer")
     */
    private int $candidateNumber;
    /**
     * @ORM\Column(type="integer")
     */
    private int $sequenceNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Activity", fetch="EAGER")
     */
    private Activity $activity;

    public function __construct(Id $id, Activity $activity, Email $email, StringValueObject $participantName, CandidateNumber $candidateNumber, SequenceNumber $sequenceNumber)
    {
        $this->id = $id;
        $this->email = $email->value();
        $this->participantName = $participantName->value();
        $this->activity = $activity;
        $this->candidateNumber = $candidateNumber->value();
        $this->sequenceNumber = $sequenceNumber->value();
    }

    public function email(): Email
    {
        return Email::fromString($this->email);
    }

    public function name(): StringValueObject
    {
        return StringValueObject::fromString($this->participantName);
    }

    public function number(): CandidateNumber
    {
        return CandidateNumber::fromInt($this->candidateNumber);
    }

}