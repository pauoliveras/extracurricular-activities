<?php

namespace App\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class Id
{
    private $id;

    public function __construct(?string $id = null)
    {
        $this->guardAgainstInvalidUuid($id);
        $this->id = $id === null ? Uuid::uuid4()->toString() : $id;
    }

    public static function next()
    {
        return new self();
    }

    public static function fromString(?string $value)
    {
        return new self($value);
    }

    public function __toString()
    {
        return (string)$this->value();
    }

    public function value(): string
    {
        return $this->id;
    }

    private function guardAgainstInvalidUuid(?string $id)
    {
        if ($id === null || Uuid::isValid($id)) {
            return;
        }

        throw new InvalidArgumentException(sprintf('%s is not a valid uuid', $id));
    }

}