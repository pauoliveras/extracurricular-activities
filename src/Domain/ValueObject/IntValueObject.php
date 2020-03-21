<?php

namespace App\Domain\ValueObject;

class IntValueObject
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function fromInt(int $value)
    {
        return new static($value);
    }

    public function value()
    {
        return $this->value;
    }
}