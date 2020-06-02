<?php

namespace App\Domain;

use App\Domain\ValueObject\CandidateCode;
use App\Domain\ValueObject\Id;

interface CandidateRepository
{
    public function save(Candidate $candidate);

    public function findByCode(CandidateCode $code): Candidate;

    public function findAll(): CandidateCollection;

    public function nextId(): Id;
}