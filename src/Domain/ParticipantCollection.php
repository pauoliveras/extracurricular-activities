<?php

namespace App\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class ParticipantCollection extends ArrayCollection
{
    public function __construct(array $elements)
    {
        Assert::allIsInstanceOf($elements, Participant::class);

        parent::__construct($elements);
    }

    public static function create(array $participants)
    {
        return new self($participants);
    }
}