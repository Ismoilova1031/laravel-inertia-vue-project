<?php

namespace App\Infrastructure\Query;

use App\Application\UseCase\Course\CourseDetail;
use App\Application\UseCase\Course\CourseListItem;
use App\Application\UseCase\Course\CourseQueryInterface;
use App\Infrastructure\Eloquent\CourseModel;

final class EloquentCourseQuery implements CourseQueryInterface
{
    public function all(): array
    {
        return CourseModel::query()
            ->orderBy('id')
            ->get()
            ->map(fn (CourseModel $m) => new CourseListItem(
                (int) $m->id, $m->title, $m->description, $m->category, $m->status,
            ))
            ->all();
    }

    public function detail(int $id): ?CourseDetail
    {
        $m = CourseModel::with(['lessons', 'students'])->find($id);

        return $m ? new CourseDetail(
            (int) $m->id, $m->title, $m->description, $m->category, $m->status,
            $m->lessons, $m->students,
        ) : null;
    }
}
