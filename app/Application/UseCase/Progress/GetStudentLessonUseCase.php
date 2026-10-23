<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exception\StudentNotActiveInCourseException;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Progress\LessonProgressRepositoryInterface;
use App\Domain\Student\StudentId;

final class GetStudentLessonUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly CourseRepositoryInterface $courses,
        private readonly LessonRepositoryInterface $lessons,
        private readonly LessonProgressRepositoryInterface $progress,
    ){}

    public function execute(int $studentId, int $lessonId): StudentLessonView
    {
        $studentId = new StudentId($studentId);
        $lessonId = new LessonId($lessonId);

        $lesson = $this->lessons->findById($lessonId) ?? throw LessonNotFoundException::withId($lessonId);

        $enrollment = $this->enrollments->findByCourseAndStudent($lesson->courseId(), $studentId);
        if($enrollment === null || !$enrollment->isActive()){
            throw StudentNotActiveInCourseException::create();
        }

        $course = $this->courses->findById($lesson->courseId())
            ?? throw CourseNotFoundException::withId($lesson->courseId());

        $siblings = $this->lessons->findByCourse($lesson->courseId());
        $index = array_search($lessonId->value(), array_map(fn (Lesson $lesson) => $lesson->id()->value(), $siblings), true);

        return new StudentLessonView(
            $course,
            $lesson,
            $this->progress->findByLessonAndStudent($lessonId, $studentId) !== null,
            $siblings[$index - 1] ?? null,
            $siblings[$index + 1] ?? null,
        );
    }
}