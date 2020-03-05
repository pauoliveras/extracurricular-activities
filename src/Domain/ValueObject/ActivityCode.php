<?php


namespace App\Domain\ValueObject;


class ActivityCode
{
    private $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function fromString(string $code): self
    {
        return new self($code);
    }

    public function value(): string
    {
        return $this->code;
    }
}