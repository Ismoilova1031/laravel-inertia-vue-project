<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Course\VO\CourseId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Progress\CourseProgress;
use App\Domain\Student\VO\StudentId;

final class CourseCompletion
{
    public function __construct(
        private readonly LessonRepositoryInterface $lessons,
        private readonly LessonCompletionResolver $completion
    ) {}

    public function progress(StudentId $studentId, CourseId $courseId): CourseProgress
    {
        $lessons = $this->lessons->findByCourse($courseId);

        return CourseProgress::of($lessons, $this->completion->completedIds($studentId, $lessons));
    }
}