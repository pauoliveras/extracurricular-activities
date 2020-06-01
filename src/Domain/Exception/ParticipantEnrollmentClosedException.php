<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject\ActivityCode;
use Exception;

class ParticipantEnrollmentClosedException extends Exception
{

    public static function ofActivity(ActivityCode $activityCode)
    {
        return new self(
            sprintf('Activity %s is closed for new enrollments', $activityCode->value())
        );
    }
}