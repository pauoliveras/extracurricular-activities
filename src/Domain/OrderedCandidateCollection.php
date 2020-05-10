<?php

namespace App\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class OrderedCandidateCollection extends ArrayCollection
{
    public function __construct(array $elements)
    {
        Assert::allIsInstanceOf($elements, Candidate::class);

        parent::__construct($elements);
    }
}