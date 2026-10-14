<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Course\Course;
use App\Domain\Course\CourseDescription;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseTitle;
use App\Infrastructure\Eloquent\CourseModel;

final class CourseMapper
{
    public function toEntity(CourseModel $model): Course
    {
        return Course::reconstitute(
            new CourseId((int) $model->id),
            new CourseTitle($model->title),
            new CourseDescription($model->description),
            $model->category,
            $model->status,
        );
    }

    public function toAttributes(Course $course): array
    {
        return [
            'title' => $course->title()->value(),
            'description' => $course->description()->value(),
            'category' => $course->category(),
            'status' => $course->status(),
        ];
    }
}
