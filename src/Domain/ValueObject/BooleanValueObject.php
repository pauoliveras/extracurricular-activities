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

    public static function true()
    {
        return new self(true);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function equals(BooleanValueObject $other)
    {
        return $this->value === $other->value;
    }

    public function isTrue(): bool
    {
        return $this->value === true;
    }

    public function isFalse(): bool
    {
        return $this->value === false;
    }
}