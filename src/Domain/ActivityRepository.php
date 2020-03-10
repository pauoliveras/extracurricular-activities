<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;

interface ActivityRepository
{
    public function findByCode(ActivityCode $code): Activity;

}