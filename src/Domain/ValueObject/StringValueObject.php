<?php

namespace App\Domain\ValueObject;

class StringValueObject
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $value): self
    {
        return new static($value);
    }

    public function __toString()
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(StringValueObject $other)
    {
        return $this->value === $other->value;
    }
}