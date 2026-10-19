<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\Course;
use App\Domain\Course\NewCourse;
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

    public function create(NewCourse $new): Course
    {
        $model = CourseModel::create([
            'title'       => $new->title->value(),
            'description' => $new->description->value(),
            'category'    => $new->category,
            'status'      => $new->status(),
        ]);

        return $this->mapper->toEntity($model);
    }


    public function save(Course $course): void
    {

        $model = CourseModel::find($course->id()->value())
            ?? throw CourseNotFoundException::withId($course->id());

        $model->update($this->mapper->toAttributes($course));
    }

    public function delete(CourseId $id): void
    {
        CourseModel::whereKey($id->value())->delete();
    }
}
