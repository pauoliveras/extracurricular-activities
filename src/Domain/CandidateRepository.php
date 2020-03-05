<?php

namespace App\Domain;

interface CandidateRepository
{
    public function save(Candidate $candidate);
}