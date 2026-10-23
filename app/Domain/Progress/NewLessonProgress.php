<?php

namespace App\Domain\Progress;

use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonType;
use App\Domain\Progress\Exception\LessonNotCompletableManuallyException;
use App\Domain\Student\StudentId;
use DateTimeImmutable;

final class NewLessonProgress
{
    private function __construct(
        public readonly LessonId $lessonId,
        public readonly StudentId $studentId,
        public readonly DateTimeImmutable $completedAt,
    ) {}

    public static function complete(Lesson $lesson, StudentId $studentId, DateTimeImmutable $now): self
    {
        if($lesson->type() === LessonType::TASK){
            throw new LessonNotCompletableManuallyException();
        }

        return new self($lesson->id(), $studentId, $now);
    } 
}