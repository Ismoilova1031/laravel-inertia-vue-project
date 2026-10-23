<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exception\StudentNotActiveInCourseException;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Progress\LessonProgressRepositoryInterface;
use App\Domain\Progress\NewLessonProgress;
use App\Domain\Student\StudentId;
use DateTimeImmutable;

final class MarkLessonCompletedUseCase
{
    public function __construct(
        private readonly LessonRepositoryInterface $lessons,
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly LessonProgressRepositoryInterface $progress,
    ){}

    public function execute(int $lessonId, int $studentId): void
    {
        $lessonId = new LessonId($lessonId);
        $studentId = new StudentId($studentId);

        $lesson = $this->lessons->findById($lessonId) ?? throw lessonNotFoundException::withId($lessonId);

        $enrollment = $this->enrollments->findByCourseAndStudent($lesson->courseId(), $studentId);
        if($enrollment === null || !$enrollment->isActive()){
            throw StudentNotActiveInCourseException::create();
        }

        if($this->progress->findByLessonAndStudent($lessonId, $studentId) !== null){
            return;
        }

        $this->progress->create(NewlessonProgress::complete($lesson, $studentId, new DateTimeImmutable()));
    }
}