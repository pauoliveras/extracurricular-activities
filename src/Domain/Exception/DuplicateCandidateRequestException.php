<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject\CandidateCode;
use Exception;

class DuplicateCandidateRequestException extends Exception
{

    public static function candidate(CandidateCode $code)
    {
        return new self(sprintf('Candidate of email %s has already placed a request', (string)$code));
    }
}