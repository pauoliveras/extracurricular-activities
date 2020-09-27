<?php

namespace App\Tests\Unit\Application\Query;

use App\Application\GetCandidatesOrderedByNumberQueryHandler;
use App\Application\Query\GetCandidatesOrderedByNumberQuery;
use App\Domain\Candidate;
use App\Domain\CandidateCollection;
use App\Domain\ValueObject\BooleanValueObject;
use App\Domain\ValueObject\CandidateNumber;
use App\Domain\ValueObject\LuckyDrawNumber;
use App\Tests\Infrastructure\Stubs\CandidateStubBuilder;
use App\Tests\Infrastructure\Stubs\InMemoryCandidateRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GetCandidatesOrderedByNumberQueryHandlerTest extends TestCase
{
    private $queryHandler;
    private $candidateRepository;

    public function test_when_no_candidates_empty_collection_is_returned()
    {
        $query = new GetCandidatesOrderedByNumberQuery(LuckyDrawNumber::fromInt(random_int(1, 10))->value());

        $orderedCandidates = $this->queryHandler->__invoke($query);

        $this->assertTrue($orderedCandidates->isEmpty());
    }

    public function test_number_can_not_be_greater_than_number_of_candidates()
    {
        $this->givenThereAreNCandidates(10);
        $query = new GetCandidatesOrderedByNumberQuery(LuckyDrawNumber::fromInt(11)->value());

        $this->expectException(InvalidArgumentException::class);

        $this->queryHandler->__invoke($query);
    }

    private function givenThereAreNCandidates(int $numberOfCandidates)
    {
        $candidateStubBuilder = new CandidateStubBuilder();
        for ($i = 0; $i < $numberOfCandidates; $i++) {
            $candidates[] = $candidateStubBuilder->withNumber(CandidateNumber::fromInt($i + 1))->build();
        }

        shuffle($candidates);

        foreach ($candidates as $candidate) {
            $this->candidateRepository->save($candidate);
        }
    }

    public function test_candidates_are_returned_in_order_starting_at_number_one()
    {
        $this->givenThereAreNCandidates(10);
        $query = new GetCandidatesOrderedByNumberQuery(LuckyDrawNumber::fromInt(1)->value());

        $orderedCandidates = $this->queryHandler->__invoke($query);

        $this->assertEquals(
            range(1, 10),
            $this->candidateNumbersAsArray($orderedCandidates)
        );
    }

    /**
     * @param CandidateCollection $processedCandidates
     * @return array
     */
    protected function candidateNumbersAsArray(CandidateCollection $processedCandidates): array
    {
        return array_values(array_map(
            function (Candidate $candidate) {
                return $candidate->number()->value();
            },
            $processedCandidates->toArray()
        ));
    }

    public function test_candidates_are_returned_in_order_starting_at_last_candidate_number()
    {
        $this->givenThereAreNCandidates(10);
        $query = new GetCandidatesOrderedByNumberQuery(LuckyDrawNumber::fromInt(10)->value());

        $orderedCandidates = $this->queryHandler->__invoke($query);

        $this->assertEquals(
            [10, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            $this->candidateNumbersAsArray($orderedCandidates)
        );
    }

    public function test_candidates_are_returned_in_order_starting_at_lucky_draw_number()
    {
        $this->givenThereAreNCandidates(10);
        $query = new GetCandidatesOrderedByNumberQuery(LuckyDrawNumber::fromInt(6)->value());

        $orderedCandidates = $this->queryHandler->__invoke($query);

        $this->assertEquals(
            [6, 7, 8, 9, 10, 1, 2, 3, 4, 5],
            $this->candidateNumbersAsArray($orderedCandidates)
        );
    }

    public function test_member_candidates_have_preference_over_non_member_candidates()
    {
        $numberOfCandidates = 10;

        $candidateStubBuilder = new CandidateStubBuilder();
        for ($i = 0; $i < $numberOfCandidates; $i++) {
            $candidates[] = $candidateStubBuilder
                ->withNumber(CandidateNumber::fromInt($i + 1))
                ->withMembership(BooleanValueObject::fromValue($i % 2 != 0))
                ->build();
        }

        shuffle($candidates);

        foreach ($candidates as $candidate) {
            $this->candidateRepository->save($candidate);
        }

        $query = new GetCandidatesOrderedByNumberQuery(LuckyDrawNumber::fromInt(6)->value());

        $orderedCandidates = $this->queryHandler->__invoke($query);

        $this->assertEquals(
            [6, 8, 10, 2, 4, 7, 9, 1, 3, 5],
            $this->candidateNumbersAsArray($orderedCandidates)
        );
    }

    protected function setUp(): void
    {
        $this->candidateRepository = new InMemoryCandidateRepository();
        $this->queryHandler = new GetCandidatesOrderedByNumberQueryHandler($this->candidateRepository);
    }
}