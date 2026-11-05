<?php

// tests/Unit/Domain/Enrollment/EnrollmentTest.php
declare(strict_types=1);

namespace Tests\Unit\Domain\Enrollment;

use App\Domain\Course\VO\CourseId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Enrollment\EnrollmentStatus;
use App\Domain\Enrollment\Exception\AlreadyEnrolledException;
use App\Domain\Enrollment\Exception\EnrollmentNotActiveException;
use App\Domain\Enrollment\NewEnrollment;
use App\Domain\Student\VO\StudentId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class EnrollmentTest extends TestCase
{
    private function newEnrollment(): Enrollment
    {
        return Enrollment::reconstitute(
            new EnrollmentId(1),
            new CourseId(1),
            new StudentId(1),
            EnrollmentStatus::ACTIVE,
            new DateTimeImmutable('2026-01-01'),
            null,
        );
    }

    public function test_new_enrollment_data_is_active(): void
    {
        $new = NewEnrollment::enroll(new CourseId(1), new StudentId(1), new DateTimeImmutable('2026-01-01'));

        $this->assertSame(EnrollmentStatus::ACTIVE, $new->status());
    }

    public function test_enrollment_is_active(): void
    {
        $this->assertTrue($this->newEnrollment()->isActive());
    }

    public function test_cannot_reenroll_while_active(): void
    {
        $this->expectException(AlreadyEnrolledException::class);

        $this->newEnrollment()->reenroll(new DateTimeImmutable('2026-02-01'));
    }

    public function test_deactivate_makes_passive_and_records_date(): void
    {
        $e = $this->newEnrollment();
        $at = new DateTimeImmutable('2026-02-01');

        $e->deactivate($at);

        $this->assertSame(EnrollmentStatus::PASSIVE, $e->status());
        $this->assertSame($at, $e->deactivatedAt());
    }

    public function test_cannot_deactivate_twice(): void
    {
        $e = $this->newEnrollment();
        $e->deactivate(new DateTimeImmutable('2026-02-01'));

        $this->expectException(EnrollmentNotActiveException::class);

        $e->deactivate(new DateTimeImmutable('2026-03-01'));
    }

    public function test_passive_student_can_be_reenrolled(): void
    {
        $e = $this->newEnrollment();
        $e->deactivate(new DateTimeImmutable('2026-02-01'));

        $e->reenroll(new DateTimeImmutable('2026-03-01'));

        $this->assertTrue($e->isActive());
        $this->assertNull($e->deactivatedAt());
    }
}
