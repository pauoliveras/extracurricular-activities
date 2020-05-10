<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\CandidateNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CandidateNumberTest extends TestCase
{
    public function test_negative_values_not_allowed()
    {
        $this->expectException(InvalidArgumentException::class);

        new CandidateNumber(-1);
    }

    public function test_minimum_number_value_is_one()
    {
        $this->expectException(InvalidArgumentException::class);

        new CandidateNumber(0);
    }
}