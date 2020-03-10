<?php

namespace App\Domain;

use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;

interface CandidateRepository
{
    public function save(Candidate $candidate);

    public function findByEmail(Email $email): Candidate;

    public function nextId(): Id;
}