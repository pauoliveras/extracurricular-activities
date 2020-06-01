<?php

namespace App\Application\Query;

use App\Domain\ValueObject\LuckyDrawNumber;

class GetCandidatesOrderedByNumberQuery
{
    private int $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function number(): LuckyDrawNumber
    {
        return LuckyDrawNumber::fromInt($this->number);
    }
}