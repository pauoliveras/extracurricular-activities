<?php

namespace App\Application\Command;

class GenerateAssignmentsCommand
{
    private int $luckyDrawNumber;

    public function __construct(int $luckyDrawNumber)
    {
        $this->luckyDrawNumber = $luckyDrawNumber;
    }

    public function luckyDrawNumber(): int
    {
        return $this->luckyDrawNumber;
    }

}