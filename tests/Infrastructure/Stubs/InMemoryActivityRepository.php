<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Activity;
use App\Domain\ActivityRepository;
use App\Domain\NullActivity;
use App\Domain\ValueObject\ActivityCode;

class InMemoryActivityRepository implements ActivityRepository
{
    private $activities = [];

    public function save(Activity $activity)
    {
        $this->activities[(string)$activity->code()] = $activity;
    }

    public function findByCode(ActivityCode $code): Activity
    {
        return isset($this->activities[(string)$code]) ? $this->activities[(string)$code] : NullActivity::createNull();
    }
}