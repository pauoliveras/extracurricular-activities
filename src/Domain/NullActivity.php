<?php

namespace App\Domain;

class NullActivity extends Activity
{
    public function __construct()
    {
    }

    public static function createNull(): self
    {
        return new self();
    }

    public function isNull(): bool
    {
        return true;
    }
}