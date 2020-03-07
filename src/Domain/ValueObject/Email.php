<?php

namespace App\Domain\ValueObject;

class Email
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public static function fromString(string $email)
    {
        return new self($email);
    }

    public function value(): string
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->email;
    }

}