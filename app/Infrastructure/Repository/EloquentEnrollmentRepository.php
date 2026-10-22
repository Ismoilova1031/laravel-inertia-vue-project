<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentId;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\NewEnrollment;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\EnrollmentModel;
use Illuminate\Support\Collection;
use RuntimeException;

class EloquentEnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Enrollment
    {
        $model = EnrollmentModel::query()
            ->where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())
            ->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function create(NewEnrollment $new): Enrollment
    {
        return $this->toEntity(EnrollmentModel::create([
            'course_id' => $new->courseId->value(),
            'student_id' => $new->studentId->value(),
            'status' => $new->status(),
            'enrolled_at' => $new->enrolledAt,
            'deactivated_at' => null,
        ]));
    }

    public function save(Enrollment $enrollment): void
    {
        $model = EnrollmentModel::find($enrollment->id()->value())
            ?? throw new RuntimeException('Enrollment topilmadi.');

        $model->update([
            'course_id' => $enrollment->courseId()->value(),
            'student_id' => $enrollment->studentId()->value(),
            'status' => $enrollment->status()->value,
            'enrolled_at' => $enrollment->enrolledAt(),
            'deactivated_at' => $enrollment->deactivatedAt(),
        ]);
    }

    private function toEntity(EnrollmentModel $model): Enrollment
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

    public function findByCourse(CourseId $courseId): Collection
    {
        return EnrollmentModel::query()->where('course_id', $courseId->value())->get()
            ->map(fn (EnrollmentModel $m) => $this->toEntity($m));
    }
}
