<?php

namespace App\Application\Command;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\StringValueObject;

class AssignCandidateToActivityCommand
{
    private string $activityCode;
    private string $candidateEmail;
    private string $candidateName;
    private int $candidateNumber;

    public function __construct(string $activityCode, string $candidateEmail, string $candidateName, int $candidateNumber)
    {
        $this->activityCode = $activityCode;
        $this->candidateEmail = $candidateEmail;
        $this->candidateName = $candidateName;
        $this->candidateNumber = $candidateNumber;
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

}