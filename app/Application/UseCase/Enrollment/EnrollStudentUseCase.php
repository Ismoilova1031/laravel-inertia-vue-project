<?php
// app/Application/Enrollment/EnrollStudent/EnrollStudentUseCase.php
declare(strict_types=1);

namespace App\Application\UseCase\Enrollment;

use App\Application\Contracts\CourseLookupInterface;
use App\Domain\Course\CourseId;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Course\Exception\CourseNotPublishedException;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\Exception\StudentNotFoundException;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepositoryInterface;
use DateTimeImmutable;

final class EnrollStudentUseCase
{
    public function __construct(
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly StudentRepositoryInterface $students,
        private readonly CourseLookupInterface $courses,
    ) {}

    public function execute(EnrollStudentCommand $command): void
    {
        $courseId = new CourseId($command->courseId);
        $studentId = new StudentId($command->studentId);

        if (!$this->courses->exists($courseId)) {
            throw CourseNotFoundException::withId($courseId);
        }

        if (!$this->courses->isPublished($courseId)) {
            throw CourseNotPublishedException::withId($courseId);
        }

        if ($this->students->findById($studentId) === null) {
            throw StudentNotFoundException::withId($studentId);
        }

        $now = new DateTimeImmutable();
        $enrollment = $this->enrollments->findByCourseAndStudent($courseId, $studentId);

        if ($enrollment === null) {
            $enrollment = Enrollment::enroll($courseId, $studentId, $now);
        } else {
            $enrollment->reenroll($now);
        }

        $this->enrollments->save($enrollment);
    }
}