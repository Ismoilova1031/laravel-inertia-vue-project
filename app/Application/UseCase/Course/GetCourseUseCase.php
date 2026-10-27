<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\VO\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Student\Student;
use App\Domain\Student\StudentRepositoryInterface;

final class GetCourseUseCase
{
    public function __construct(
        private readonly CourseRepositoryInterface $courses,
        private readonly LessonRepositoryInterface $lessons,
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly StudentRepositoryInterface $students,
    ) {}

    public function execute(int $id): CourseDetail
    {
        $courseid = new CourseId($id);
        $course = $this->courses->findById($courseid)
            ?? throw CourseNotFoundException::withId($courseid);

        $enrollments = $this->enrollments->findByCourse($courseid);
        $students = $this->students
            ->findByIds($enrollments->map(fn (Enrollment $enrollment) => $enrollment->studentId())->all())
            ->keyBy(fn (Student $student) => $student->id()->value());

        $members = $enrollments
            ->filter(fn (Enrollment $enrollment) => $students->has($enrollment->studentId()->value()))
            ->map(fn (Enrollment $enrollment) => new CourseMember($students[$enrollment->studentId()->value()], $enrollment))
            ->values()->all();

        return new CourseDetail($course, $this->lessons->findByCourse($courseid), $members);
    }
}
