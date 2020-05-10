<?php

namespace App\Application\Query;

use App\Domain\ValueObject\LuckyDrawNumber;

class GetCandidatesOrderedByNumberQuery
{
    private int $number;

    public function __construct(LuckyDrawNumber $number)
    {
        $this->number = $number->value();
    }

    public function number(): LuckyDrawNumber
    {
        return LuckyDrawNumber::fromInt($this->number);
    }
}