<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exception\EnrollmentNotFoundException;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Progress\CourseProgress;
use App\Domain\Progress\LessonProgressRepositoryInterface;
use App\Domain\Student\StudentId;

final class GetStudentCourseUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly CourseRepositoryInterface $courses,
        private readonly LessonRepositoryInterface $lessons,
        private readonly LessonCompletionResolver $completion,
    ) {}

    public function execute(int $studentId, int $courseId): StudentCourseView
    {
        $studentId = new StudentId($studentId);
        $courseId = new CourseId($courseId);

        $enrollment = $this->enrollments->findByCourseAndStudent($courseId, $studentId)
            ?? throw EnrollmentNotFoundException::create();
        $course = $this->courses->findById($courseId) ?? throw CourseNotFoundException::withId($courseId);

        $lessons = $this->lessons->findByCourse($courseId);
        $done = $this->completion->completedIds($studentId, $lessons);


        return new StudentCourseView($course, $enrollment, $lessons, $done, CourseProgress::of($lessons, $done));
    }
}