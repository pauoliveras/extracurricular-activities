<?php

namespace App\Tests\Infrastructure\Stubs;

use App\Domain\Activity;
use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Capacity;
use App\Domain\ValueObject\Id;

class ActivityStubBuilder
{
    private Id $id;
    private ActivityCode $activityCode;
    private Capacity $capacity;
    private array $participants;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->id = StubId::random();
        $this->activityCode = StubActivityCode::random();
        $this->capacity = StubCapacity::random();
    }

    public static function create()
    {
        return new self();
    }

    public function build(): Activity
    {
        $activity = Activity::create($this->id, $this->activityCode, $this->capacity);

        $this->reset();

        return $activity;
    }

    public function withCapacity(Capacity $capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function withParticipants(array $participants)
    {
        $this->participants = $participants;

        return $this;
    }
}