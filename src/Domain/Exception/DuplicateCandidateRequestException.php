<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject\Email;
use Exception;

class DuplicateCandidateRequestException extends Exception
{

    public static function candidate(Email $email)
    {
        return new self(sprintf('Candidate of email %s has already placed a request', (string)$email));
    }
}