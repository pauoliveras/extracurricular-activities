<?php

namespace App\Domain\ValueObject;

use Webmozart\Assert\Assert;

class DesiredActivityCount extends IntValueObject
{
    const MAXIMUM_DESIRED_ACTIVITIES = 5;

    public function __construct(int $value)
    {
        Assert::greaterThan($value, 0);
        Assert::lessThanEq($value, self::MAXIMUM_DESIRED_ACTIVITIES);

        parent::__construct($value);
    }

    public static function fromInt(?int $value)
    {
        return new static($value ?? self::MAXIMUM_DESIRED_ACTIVITIES);
    }

}