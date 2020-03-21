<?php

namespace App\Domain\ValueObject;

use Webmozart\Assert\Assert;

class RequestOrder extends IntValueObject
{
    private const INITIAL_ORDER_VALUE = 1;

    public function __construct(int $value)
    {
        Assert::greaterThan($value, 0);

        parent::__construct($value);
    }

    public static function initial(): self
    {
        return new self(self::INITIAL_ORDER_VALUE);
    }

    public function next(): self
    {
        return new self($this->value++);
    }
}