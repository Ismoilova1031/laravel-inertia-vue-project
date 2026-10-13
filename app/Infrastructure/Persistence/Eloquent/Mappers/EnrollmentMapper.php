<?php

namespace App\Infrastructure\Persistence\Eloquent\Mappers;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Student\StudentId;
use App\Infrastructure\Persistence\Eloquent\Models\EnrollmentModel;

final class EnrollmentMapper
{
    public function toEntity(EnrollmentModel $model): Enrollment
    {
        return Enrollment::reconstitute(
            new EnrollmentId((int) $model->id),
            new CourseId((int) $model->course_id),
            new StudentId((int) $model->student_id),
            $model->status,
            $model->enrolled_at,
            $model->deactivated_at,
        );
    }

    public function toAttributes(Enrollment $enrollment): array
    {
        return [
            'course_id' => $enrollment->courseId()->value(),
            'student_id' => $enrollment->studentId()->value(),
            'status' => $enrollment->status()->value,
            'enrolled_at' => $enrollment->enrolledAt(),
            'deactivated_at' => $enrollment->deactivatedAt(),
        ];
    }
}