<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Course\Course;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Progress\CourseProgress;
use App\Domain\Student\StudentId;

final class GetStudentCoursesUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly CourseRepositoryInterface $courses,
        private readonly LessonRepositoryInterface $lessons,
        private readonly LessonCompletionResolver $completion,
    ) {}

    /** @return list<StudentCourseItem> */
    public function execute(int $studentId): array
    {
        $studentId = new StudentId($studentId);

        $enrollments = $this->enrollments->findByStudent($studentId);
        if ($enrollments->isEmpty()) {
            return [];
        }

        $courseIds = $enrollments->map(fn (Enrollment $e) => $e->courseId())->all();
        $courses = $this->courses->findByIds($courseIds)->keyBy(fn (Course $c) => $c->id()->value());

        $lessons = $this->lessons->findByCourseIds($courseIds);
       $done = $this->completion->completedIds($studentId, $lessons->all());
        $lessonsByCourse = $lessons->groupBy(fn (Lesson $l) => $l->courseId()->value());

        return $enrollments
            ->filter(fn (Enrollment $e) => $courses->has($e->courseId()->value()))
            ->map(fn (Enrollment $e) => new StudentCourseItem(
                $courses[$e->courseId()->value()],
                $e,
                CourseProgress::of(
                    $lessonsByCourse->get($e->courseId()->value(), collect())->values()->all(),
                    $done,
                ),
            ))
            ->values()
            ->all();
    }
}