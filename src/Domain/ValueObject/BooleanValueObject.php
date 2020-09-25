<?php

namespace App\Domain\ValueObject;

class BooleanValueObject
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public static function fromValue(bool $value): self
    {
        return new static($value);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function equals(BooleanValueObject $other)
    {
        return $this->value === $other->value;
    }
}