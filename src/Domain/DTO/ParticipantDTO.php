<?php

namespace App\Domain\DTO;

use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\StringValueObject;

class ParticipantDTO
{
    public Email $email;
    public StringValueObject $name;
    public CandidateNumber $number;

    public function __construct(Email $email, StringValueObject $name, CandidateNumber $number)
    {
        $this->email = $email;
        $this->name = $name;
        $this->number = $number;
    }

    public static function createFromCandidate(Email $email, StringValueObject $name, CandidateNumber $number)
    {
        return new self($email, $name, $number);
    }
}