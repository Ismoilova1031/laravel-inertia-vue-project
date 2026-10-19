<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\NewEnrollment;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\EnrollmentModel;
use App\Infrastructure\Mapper\EnrollmentMapper;
use RuntimeException;

class EloquentEnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function __construct(private EnrollmentMapper $mapper) {}

    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Enrollment
    {
        $model = EnrollmentModel::query()
            ->where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())
            ->first();

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function create(NewEnrollment $new): Enrollment
    {
        return $this->mapper->toEntity(EnrollmentModel::create($this->mapper->toNewAttributes($new)));
    }

    public function save(Enrollment $enrollment): void
    {
        $model = EnrollmentModel::find($enrollment->id()->value())
            ?? throw new RuntimeException('Enrollment topilmadi.');

        $model->update($this->mapper->toAttributes($enrollment));
    }
}
