<?php

namespace App\Domain\Enrollment;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\Exception\AlreadyEnrolledException;
use App\Domain\Enrollment\Exception\EnrollmentNotActiveException;
use App\Domain\Student\StudentId;
use DateTimeImmutable;

final class Enrollment
{
    private function __construct(
        private ?EnrollmentId $id,
        private readonly CourseId $courseId,
        private readonly StudentId $studentId,
        private EnrollmentStatus $status,
        private DateTimeImmutable $enrolledAt,
        private ?DateTimeImmutable $deactivatedAt,
    ) {}

    public static function enroll(CourseId $courseId, StudentId $studentId, DateTimeImmutable $now): self
    {
        return new self(null, $courseId, $studentId, EnrollmentStatus::ACTIVE, $now, null);
    }

    public static function reconstitute(EnrollmentId $id, CourseId $courseId, StudentId $studentId, EnrollmentStatus $status, DateTimeImmutable $enrolledAt, ?DateTimeImmutable $deactivatedAt): self
    {
        return new self($id, $courseId, $studentId, $status, $enrolledAt, $deactivatedAt);
    }

    public function reenroll(DateTimeImmutable $now): void
    {
        if($this->isActive()){
            throw AlreadyEnrolledException::create();
        }

        $this->status = EnrollmentStatus::ACTIVE;
        $this->enrolledAt = $now;
        $this->deactivatedAt = null;
    }

    public function deactivate(DateTimeImmutable $now): void
    {
        if(!$this->isActive()){
            throw EnrollmentNotActiveException::create();
        }

        $this->status = EnrollmentStatus::PASSIVE;
        $this->deactivatedAt = $now;
    }

    public function isActive(): bool
    {
        return $this->status === EnrollmentStatus::ACTIVE;
    }

    public function id(): ?EnrollmentId { return $this->id; }
    public function courseId(): CourseId { return $this->courseId; }
    public function studentId(): StudentId { return $this->studentId; }
    public function status(): EnrollmentStatus { return $this->status; }
    public function enrolledAt(): DateTimeImmutable { return $this->enrolledAt; }
    public function deactivatedAt(): ?DateTimeImmutable { return $this->deactivatedAt; }
}