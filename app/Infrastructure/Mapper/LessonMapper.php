<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Course\CourseId;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonContent;
use App\Domain\Lesson\LessonDescription;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonTitle;
use App\Domain\Lesson\NewLesson;
use App\Domain\Lesson\SortOrder;
use App\Infrastructure\Eloquent\LessonModel;

final class LessonMapper
{
    public function toEntity(LessonModel $model): Lesson
    {
        return Lesson::reconstitute(
            new LessonId((int) $model->id),
            new CourseId((int) $model->course_id),
            new LessonTitle($model->title),
            new LessonDescription($model->description),
            LessonContent::of($model->lesson_type, $model->lesson_content, $model->video_url),
            new SortOrder((int) $model->sort_order)
        );
    }

    public function toAttributes(Lesson $lesson): array
    {
        return [
            'course_id' => $lesson->courseId()->value(),
            'title' => $lesson->title()->value(),
            'description' => $lesson->description()->value(),
            'lesson_type' => $lesson->type(),
            'lesson_content' => $lesson->content(),
            'video_url' => $lesson->videoPath(),
            'sort_order' => $lesson->sortOrder()->value(),
        ];
    }

    public function toNewAttributes(NewLesson $new): array
    {
        return [
            'course_id' => $new->courseId->value(),
            'title' => $new->title->value(),
            'description' => $new->description->value(),
            'lesson_type' => $new->content->type(),
            'lesson_content' => $new->content->text(),
            'video_url' => $new->content->videoPath(),
            'sort_order' => $new->sortOrder->value(),
        ];
    }
}
