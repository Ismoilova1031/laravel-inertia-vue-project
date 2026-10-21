<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\CourseTitle;
use App\Domain\Course\CourseDescription;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Course\NewCourse;
use App\Infrastructure\Eloquent\CourseModel;

final class EloquentCourseRepository implements CourseRepositoryInterface
{
    public function findById(CourseId $id): ?Course
    {
        $model = CourseModel::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function create(NewCourse $new): Course
    {
        $model = CourseModel::create([
            'title' => $new->title->value(),
            'description' => $new->description->value(),
            'category' => $new->category,
            'status' => $new->status(),
        ]);

        return $this->toEntity($model);
    }

    public function save(Course $course): void
    {

        $model = CourseModel::find($course->id()->value())
            ?? throw CourseNotFoundException::withId($course->id());

        $model->update(
            [
                'title' => $course->title()->value(),
                'description' => $course->description()->value(),
                'category' => $course->category(),
                'status' => $course->status(),
            ]
        );
    }

    public function delete(CourseId $id): void
    {
        CourseModel::whereKey($id->value())->delete();
    }

    private function toEntity(CourseModel $model): Course
    {
        return Course::reconstitute(
            new CourseId((int) $model->id),
            new CourseTitle($model->title),
            new CourseDescription($model->description),
            $model->category,
            $model->status,
        );
    }
}
