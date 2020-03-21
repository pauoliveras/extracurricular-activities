<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

class RequestedActivitiesList implements Countable, IteratorAggregate
{
    private $activities = [];

    private function __construct(array $activities)
    {
        foreach ($activities as $activity) {
            $activityCode = ActivityCode::fromString($activity);
            if (in_array($activityCode, $this->activities)) {
                throw new InvalidArgumentException('Duplicated activities found');
            }
            $this->activities[] = $activityCode;
        }
    }

    public static function createFromArray(array $activities)
    {
        return new self($activities);
    }

    public function toArray(): array
    {
        return array_map(function (ActivityCode $activityCode) {
            return $activityCode->value();
        }, $this->activities);
    }

    public function activityCodeAtPosition(int $index): ActivityCode
    {
        return $this->activities[$index - 1];
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->activities);
    }

    public function isEmpty()
    {
        return count($this->activities) === 0;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->activities);
    }
}