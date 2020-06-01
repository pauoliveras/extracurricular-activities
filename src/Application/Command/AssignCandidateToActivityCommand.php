<?php

namespace App\Application\Command;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\StringValueObject;

class AssignCandidateToActivityCommand
{
    private string $activityCode;
    private string $candidateEmail;
    private string $candidateName;

    public function __construct(string $activityCode, string $candidateEmail, string $candidateName)
    {
        $this->activityCode = $activityCode;
        $this->candidateEmail = $candidateEmail;
        $this->candidateName = $candidateName;
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
}