<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Course\CourseId;
use App\Domain\CourseInterest\CourseInterest;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\Student\StudentId;
use App\Infrastructure\Persistence\Eloquent\Mappers\CourseInterestMapper;
use App\Infrastructure\Persistence\Eloquent\Models\CourseInterestModel;
use RuntimeException;

final class EloquentCourseInterestRepository implements CourseInterestRepositoryInterface
{
    public function __construct(private readonly CourseInterestMapper $mapper) {}

    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?CourseInterest
    {
        $model = CourseInterestModel::query()
            ->where('course_id', $courseId->value())
            ->where('student_id', $studentId->value())
            ->first();

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function save(CourseInterest $interest): CourseInterest
    {
        $attributes = $this->mapper->toAttributes($interest);

        if ($interest->id() === null) {
            $model = CourseInterestModel::create($attributes);
        } else {
            $model = CourseInterestModel::find($interest->id())
                ?? throw new RuntimeException('CourseInterest topilmadi.');

            $model->update($attributes);
        }

        return $this->mapper->toEntity($model);
    }

    public function delete(CourseInterest $interest): void
    {
        if ($interest->id() !== null) {
            CourseInterestModel::whereKey($interest->id())->delete();
        }
    }
}