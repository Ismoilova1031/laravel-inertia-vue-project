<?php

namespace App\Application\UseCase\Enrollment;

use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\NewEnrollment;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use DateTimeImmutable;

final class EnrollStudentUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly StudentRepositoryInterface $students,
        private readonly CourseRepositoryInterface $courses,
    ) {}

    public function execute(EnrollStudentCommand $command): void
    {
        $courseId = new CourseId($command->courseId);
        $studentId = new StudentId($command->studentId);

        $course = $this->courses->findById($courseId)
            ?? throw CourseNotFoundException::withId($courseId);

        if (! $course->isPublished()) {
            throw CourseNotPublishedException::withId($courseId);
        }

        if ($this->students->findById($studentId) === null) {
            throw StudentNotFoundException::withId($studentId);
        }

        $now = new DateTimeImmutable;
        $enrollment = $this->enrollments->findByCourseAndStudent($courseId, $studentId);

        if ($enrollment === null) {
            $this->enrollments->create(NewEnrollment::enroll($courseId, $studentId, $now));

            return;
        }

        $enrollment->reenroll($now);
        $this->enrollments->save($enrollment);
    }
}
