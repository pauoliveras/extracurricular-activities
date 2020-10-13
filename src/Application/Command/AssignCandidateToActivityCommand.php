<?php

namespace App\Application\Command;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\SequenceNumber;
use App\Domain\ValueObject\StringValueObject;

class AssignCandidateToActivityCommand
{
    private string $activityCode;
    private string $candidateEmail;
    private string $candidateName;
    private int $candidateNumber;
    private int $sequenceNumber;
    private string $candidateId;

    public function __construct(
        string $activityCode,
        string $candidateEmail,
        string $candidateName,
        int $candidateNumber,
        string $candidateId,
        int $sequenceNumber
    )
    {
        $this->activityCode = $activityCode;
        $this->candidateEmail = $candidateEmail;
        $this->candidateName = $candidateName;
        $this->candidateNumber = $candidateNumber;
        $this->candidateId = $candidateId;
        $this->sequenceNumber = $sequenceNumber;
    }

    public function activityCode(): ActivityCode
    {
        return ActivityCode::fromString($this->activityCode);
    }

    public function candidateEmail(): Email
    {
        return Email::fromString($this->candidateEmail);
    }

    public function candidateName(): StringValueObject
    {
        return StringValueObject::fromString($this->candidateName);
    }

    public function candidateNumber(): CandidateNumber
    {
        return CandidateNumber::fromInt($this->candidateNumber);
    }

    public function sequenceNumber(): SequenceNumber
    {
        return SequenceNumber::fromInt($this->sequenceNumber);
    }

    public function candidateId(): Id
    {
        return Id::fromString($this->candidateId);
    }

}