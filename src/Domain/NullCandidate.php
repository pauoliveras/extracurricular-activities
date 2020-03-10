<?php

namespace App\Domain;

class NullCandidate extends Candidate
{
    public function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    public function isNull(): bool
    {
        return true;
    }
}