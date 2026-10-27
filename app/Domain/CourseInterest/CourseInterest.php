<?php

namespace App\Domain\CourseInterest;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;

final class CourseInterest
{
    public function __construct(
        private int $id,
        private CourseId $courseId,
        private StudentId $studentId,
        private array $flags,
    ) {}

    public static function reconstitute(
        int $id,
        CourseId $courseId,
        StudentId $studentId,
        bool $liked,
        bool $planned,
    ) {
        return new self($id, $courseId, $studentId, [
            InterestType::LIKE->value => $liked,
            InterestType::PLANNED->value => $planned,
        ]);
    }

    public function mark(InterestType $type): void
    {
        $this->flags[$type->value] = true;
    }

    public function unmark(InterestType $type): void
    {
        $this->flags[$type->value] = false;
    }

    public function has(InterestType $type): bool
    {
        return $this->flags[$type->value];
    }

    public function isEmpty(): bool
    {
        return ! in_array(true, $this->flags, true);
    }

    public function id(): int
    {
        return $this->id;
    }

    public function courseId(): CourseId
    {
        return $this->courseId;
    }

    public function studentId(): StudentId
    {
        return $this->studentId;
    }
}
