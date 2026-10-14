<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\StudentId;
use App\Infrastructure\Mapper\EnrollmentMapper;
use App\Infrastructure\Eloquent\EnrollmentModel;
use RuntimeException;

class EloquentEnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function __construct(private EnrollmentMapper $mapper){}

    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Enrollment
    {
        $model = EnrollmentModel::query()
            ->where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())
            ->first();

            return $model ? $this->mapper->toEntity($model) : null;
    }

    public function save(Enrollment $enrollment): Enrollment
    {
        $attributes = $this->mapper->toAttributes($enrollment);

        if($enrollment->id() === null){
            $model = EnrollmentModel::create($attributes);
        } else {
            $model = EnrollmentModel::find($enrollment->id()->value())
                ?? throw new RuntimeException('Enrollment topilmadi.');

            $model->update($attributes);
        }

        return $this->mapper->toEntity($model);
    }
}