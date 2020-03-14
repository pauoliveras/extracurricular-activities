<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;

interface ActivityRepository
{
    public function ofId(Id $id): Activity;

    public function findByCode(ActivityCode $code): Activity;

    public function save(Activity $activity);

}