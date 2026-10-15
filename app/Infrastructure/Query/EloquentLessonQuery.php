<?php

namespace App\Infrastructure\Query;

use App\Application\UseCase\Lesson\LessonEditView;
use App\Application\UseCase\Lesson\LessonQueryInterface;
use App\Infrastructure\Eloquent\LessonModel;

final class EloquentLessonQuery implements LessonQueryInterface
{
    public function forEdit(int $lessonId): ?LessonEditView
    {
        $m = LessonModel::with('task')->find($lessonId);

        return $m ? new LessonEditView(
            (int) $m->id, $m->title, $m->description, $m->lesson_type,
            $m->lesson_content, $m->video_url, (int) $m->sort_order, $m->task,
        ) : null;
    }
}