<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Mapper\CourseMapper;

final class EloquentCourseRepository implements CourseRepositoryInterface
{
    public function __construct(private readonly CourseMapper $mapper) {}

    public function findById(CourseId $id): ?Course
    {
        $model = CourseModel::find($id->value());

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function save(Course $course): Course
    {
        $attributes = $this->mapper->toAttributes($course);

        if ($course->id() === null) {
            $model = CourseModel::create($attributes);
        } else {
            $model = CourseModel::find($course->id()->value())
                ?? throw CourseNotFoundException::withId($course->id());

            $model->update($attributes);
        }

        return $this->mapper->toEntity($model);
    }

    public function delete(CourseId $id): void
    {
        CourseModel::whereKey($id->value())->delete();
    }
}
