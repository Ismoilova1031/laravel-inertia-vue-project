<?php

namespace App\Application\UseCase\Enrollment;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exception\EnrollmentNotFoundException;
use App\Domain\Student\StudentId;
use DateTimeImmutable;

final class DeactivateEnrollmentUseCase
{
    public function __construct(private readonly EnrollmentRepositoryInterface $enrollments) {}

    public function execute(DeactivateEnrollmentCommand $command): void
    {
        $enrollment = $this->enrollments->findByCourseAndStudent(
            new CourseId($command->courseId),
            new StudentId($command->studentId),
        ) ?? throw EnrollmentNotFoundException::create();

        $enrollment->deactivate(new DateTimeImmutable);

        $this->enrollments->save($enrollment);
    }
}
