<?php

namespace App\Infrastructure\Repository;

use App\Domain\Course\CourseId;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonContent;
use App\Domain\Lesson\LessonDescription;
use App\Domain\Lesson\LessonTitle;
use App\Domain\Lesson\SortOrder;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\NewLesson;
use App\Infrastructure\Eloquent\LessonModel;


final class EloquentLessonRepository implements LessonRepositoryInterface
{

    public function findById(LessonId $id): ?Lesson
    {
        $model = LessonModel::find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function findByCourse(CourseId $courseId): array
    {
        return LessonModel::query()
            ->where('course_id', $courseId->value())
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn(LessonModel $m) => $this->toEntity($m))
            ->all();
    }

    public function create(NewLesson $new): Lesson
    {
        $model = LessonModel::create([
            'course_id' => $new->courseId->value(),
            'title' => $new->title->value(),
            'description' => $new->description->value(),
            'lesson_type' => $new->content->type(),
            'lesson_content' => $new->content->text(),
            'video_url' => $new->content->videoPath(),
            'sort_order' => $new->sortOrder->value(),
        ]);

        return $this->toEntity($model);
    }

    public function save(Lesson $lesson): void
    {
        $model = LessonModel::find($lesson->id()->value())
            ?? throw LessonNotFoundException::withId($lesson->id());

        $model->update([
            'course_id' => $lesson->courseId()->value(),
            'title' => $lesson->title()->value(),
            'description' => $lesson->description()->value(),
            'lesson_type' => $lesson->type(),
            'lesson_content' => $lesson->content(),
            'video_url' => $lesson->videoPath(),
            'sort_order' => $lesson->sortOrder()->value(),
        ]);
    }

    public function delete(LessonId $id): void
    {
        LessonModel::whereKey($id->value())->delete();
    }

    private function toEntity(LessonModel $model): Lesson
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
}
