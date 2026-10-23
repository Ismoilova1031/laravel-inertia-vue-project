<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Course\Course;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Progress\CourseProgress;
use App\Domain\Progress\LessonProgressRepositoryInterface;
use App\Domain\Student\StudentId;

final class GetStudentCoursesUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly CourseRepositoryInterface $courses,
        private readonly LessonRepositoryInterface $lessons,
        private readonly LessonProgressRepositoryInterface $progress,
    ){}

    public function execute(int $studentId): array
    {
        $studentId = new StudentId($studentId);

        $enrollments = $this->enrollments->findByStudent($studentId);
        if($enrollments->isEmpty()){
            return [];
        }

        $courseIds = $enrollments->map(fn (Enrollment $enrollment) => $enrollment->courseId())->all();
        $courses = $this->courses->findByIds($courseIds)->keyBy(fn (Course $course) => $course->id()->value());

        $lessons = $this->lessons->findByCourseIds($courseIds);
        $done = $this->progress->completedLessonIds(
            $studentId,
            $lessons->map(fn (Lesson $lesson) => $lesson->id())->all(),
        );
        $lessonsByCourse = $lessons->groupBy(fn (Lesson $lesson) => $lesson->courseId()->value());

        return $enrollments
            ->filter(fn (Enrollment $enrollment) => $courses->has($enrollment->courseId()->value()))
            ->map(fn (Enrollment $enrollment) => new StudentCourseItem(
                $courses[$enrollment->courseId()->value()],
                $enrollment,
                CourseProgress::of(
                    $lessonsByCourse->get($enrollment->courseId()->value(), collect())->values()->all(),
                    $done,
                ),
            ))
            ->values()
            ->all();
    }
}