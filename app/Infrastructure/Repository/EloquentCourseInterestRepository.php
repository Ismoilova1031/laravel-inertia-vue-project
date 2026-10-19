<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\CourseId;
use App\Domain\CourseInterest\CourseInterest;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\CourseInterest\NewCourseInterest;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\CourseInterestModel;
use App\Infrastructure\Mapper\CourseInterestMapper;
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

    public function create(NewCourseInterest $new): CourseInterest
    {
        return $this->mapper->toEntity(CourseInterestModel::create($this->mapper->toNewAttributes($new)));
    }

    public function save(CourseInterest $interest): void
    {
        $model = CourseInterestModel::find($interest->id())
            ?? throw new RuntimeException('CourseInterest topilmadi.');

        $model->update($this->mapper->toAttributes($interest));
    }

    public function delete(CourseInterest $interest): void
    {
        CourseInterestModel::whereKey($interest->id())->delete();
    }
}
