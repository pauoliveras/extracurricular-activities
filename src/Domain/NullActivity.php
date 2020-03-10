<?php

namespace App\Domain;

class NullActivity extends Activity
{
    public static function create(): self
    {
        return new self();
    }

    public function isNull(): bool
    {
        return true;
    }
}