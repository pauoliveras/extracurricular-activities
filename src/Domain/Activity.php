<?php

namespace App\Domain;

use App\Domain\ValueObject\ActivityCode;
use App\Domain\ValueObject\Id;

class Activity
{
    private $id;

    private $code;

    public function __construct(Id $id, ActivityCode $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    public function isNull(): bool
    {
        return false;
    }
}