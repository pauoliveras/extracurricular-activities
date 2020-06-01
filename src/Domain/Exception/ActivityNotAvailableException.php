<?php

namespace App\Domain\Exception;

use Exception;

class ActivityNotAvailableException extends Exception
{

    public static function full(string $activityCode)
    {
        return new self(sprintf('Activity of code %s is full', $activityCode));
    }
}