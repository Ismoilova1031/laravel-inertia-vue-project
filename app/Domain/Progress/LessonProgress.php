<?php

namespace App\Domain\Progress;

use App\Domain\Lesson\LessonId;
use App\Domain\Student\StudentId;
use DateTimeImmutable;

final class LessonProgress
{
    private function __construct(
        private readonly LessonProgressId $id,
        private readonly LessonId $lessonId,
        private readonly StudentId $studentId,
        private readonly DateTimeImmutable $completedAt
    ){}

    public static function reconstitute(
        LessonProgressId $id,
        LessonId $lessonId,
        StudentId $studentId,
        DateTimeImmutable $completedAt,
    ): self {
        return new self($id, $lessonId, $studentId, $completedAt);
    }

    public function id(): LessonProgressId { return $this->id; }
    public function lessonId(): LessonId { return $this->lessonId; }
    public function studentId(): StudentId { return $this->studentId; }
    public function completedAt(): DateTimeImmutable { return $this->completedAt; }
}