<?php

namespace App\Domain;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\StringValueObject;

class Candidate
{
    private $email;
    private $candidateName;
    private $group;
    private $requestedActivities;

    public function __construct(
        Email $email,
        StringValueObject $candidateName,
        StringValueObject $group,
        array $requestedActivities
    )
    {
        $this->email = $email->value();
        $this->candidateName = $candidateName->value();
        $this->group = $group->value();
        $this->requestedActivities = $requestedActivities;
    }
}