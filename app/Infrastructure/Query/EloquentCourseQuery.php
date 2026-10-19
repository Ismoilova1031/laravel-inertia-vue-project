<?php

namespace App\Infrastructure\Query;

use App\Application\UseCase\Course\CourseDetail;
use App\Application\UseCase\Course\CourseListItem;
use App\Application\UseCase\Course\CourseQueryInterface;
use App\Application\UseCase\Lesson\LessonListItem;
use App\Infrastructure\Eloquent\CourseModel;
use App\Infrastructure\Eloquent\LessonModel;

final class EloquentCourseQuery implements CourseQueryInterface
{
    public function all(): array
    {
        return CourseModel::query()
            ->orderBy('id')
            ->get()
            ->map(fn (CourseModel $m) => new CourseListItem(
                (int) $m->id,
                $m->title,
                $m->description,
                $m->category,
                $m->status,
            ))
            ->all();
    }

    public function detail(int $id): ?CourseDetail
    {
        $m = CourseModel::with(['lessons', 'students'])->find($id);

        $lessons = $m->lessons->map(fn (LessonModel $l) => new LessonListItem(
            (int) $l->id,
            $l->title,
            $l->description,
            $l->lesson_type,
            $l->lesson_content,
            $l->video_url,
            (int) $l->sort_order,
        ))->all();

        return new CourseDetail(
            (int) $m->id,
            $m->title,
            $m->description,
            $m->category,
            $m->status,
            $lessons,
            $m->students,
        );
    }
}
