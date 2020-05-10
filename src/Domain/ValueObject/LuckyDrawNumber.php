<?php

namespace App\Domain\ValueObject;

use Webmozart\Assert\Assert;

class LuckyDrawNumber extends IntValueObject
{
    public function __construct(int $value)
    {
        Assert::greaterThan($value, 0);

        parent::__construct($value);
    }
}