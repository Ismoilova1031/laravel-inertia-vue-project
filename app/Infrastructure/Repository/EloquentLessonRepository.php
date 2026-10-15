<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\CourseId;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Infrastructure\Eloquent\LessonModel;
use App\Infrastructure\Mapper\LessonMapper;

final class EloquentLessonRepository implements LessonRepositoryInterface
{
    public function __construct(private readonly LessonMapper $mapper) {}

    public function findById(LessonId $id): ?Lesson
    {
        $model = LessonModel::find($id->value());

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function findByCourse(CourseId $courseId): array
    {
        return LessonModel::query()
            ->where('course_id', $courseId->value())
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (LessonModel $m) => $this->mapper->toEntity($m))
            ->all();
    }

    public function save(Lesson $lesson): Lesson
    {
        $attributes = $this->mapper->toAttributes($lesson);

        if ($lesson->id() === null) {
            $model = LessonModel::create($attributes);
        } else {
            $model = LessonModel::find($lesson->id()->value())
                ?? throw LessonNotFoundException::withId($lesson->id());

            $model->update($attributes);
        }

        return $this->mapper->toEntity($model);
    }

    public function delete(LessonId $id): void
    {
        LessonModel::whereKey($id->value())->delete();
    }
}