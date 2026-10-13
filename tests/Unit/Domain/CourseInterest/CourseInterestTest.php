<?php
// tests/Unit/Domain/CourseInterest/CourseInterestTest.php
declare(strict_types=1);

namespace Tests\Unit\Domain\CourseInterest;

use App\Domain\Course\CourseId;
use App\Domain\CourseInterest\CourseInterest;
use App\Domain\CourseInterest\InterestType;
use App\Domain\Student\StudentId;
use PHPUnit\Framework\TestCase;

final class CourseInterestTest extends TestCase
{
    private function interest(): CourseInterest
    {
        return CourseInterest::create(new CourseId(1), new StudentId(1));
    }

    public function test_new_interest_is_empty(): void
    {
        $this->assertTrue($this->interest()->isEmpty());
    }

    public function test_types_are_independent(): void
    {
        $i = $this->interest();

        $i->mark(InterestType::LIKE);

        $this->assertTrue($i->has(InterestType::LIKE));
        $this->assertFalse($i->has(InterestType::PLANNED));
    }

    public function test_unmark_affects_only_that_type(): void
    {
        $i = $this->interest();
        $i->mark(InterestType::LIKE);
        $i->mark(InterestType::PLANNED);

        $i->unmark(InterestType::LIKE);

        $this->assertFalse($i->has(InterestType::LIKE));
        $this->assertTrue($i->has(InterestType::PLANNED));
        $this->assertFalse($i->isEmpty());
    }

    public function test_mark_is_idempotent(): void
    {
        $i = $this->interest();

        $i->mark(InterestType::LIKE);
        $i->mark(InterestType::LIKE);

        $this->assertTrue($i->has(InterestType::LIKE));
    }
}