<?php

namespace App\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

class Id
{
    private $id;

    public function __construct(?string $id = null)
    {
        $this->id = $id === null ? Uuid::uuid4()->toString() : $id;
    }

    public static function next()
    {
        return new self();
    }

    public function __toString()
    {
        return (string)$this->value();
    }

    public function value(): string
    {
        return $this->id;
    }

}