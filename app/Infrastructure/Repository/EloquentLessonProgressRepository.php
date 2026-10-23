<?php

namespace App\Infrastructure\Repository;

use App\Domain\Lesson\LessonId;
use App\Domain\Progress\LessonProgress;
use App\Domain\Progress\LessonProgressId;
use App\Domain\Progress\LessonProgressRepositoryInterface;
use App\Domain\Progress\NewLessonProgress;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\LessonProgressModel;

final class EloquentLessonProgressRepository implements LessonProgressRepositoryInterface
{
    public function findByLessonAndStudent(LessonId $lessonId, StudentId $studentId): ?LessonProgress
    {
        $model = LessonProgressModel::query()
            ->where('lesson_id', $lessonId->value())
            ->where('student_id', $studentId->value())
            ->first();

            return $model ? $this->toEntity($model) : null;
    }

    public function completedLessonIds(StudentId $studentId, array $lessonIds): array
    {
        if($lessonIds === []){
            return [];
        }

        return LessonProgressModel::query()
            ->where('student_id', $studentId->value())
            ->whereIn('lesson_id', array_map(fn (Lessonid $id) => $id->value(), $lessonIds))
            ->pluck('lesson_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function create(NewLessonProgress $new): LessonProgress
    {
        return $this->toEntity(LessonProgressModel::create([
            'lesson_id' => $new->lessonId->value(),
            'student_id' => $new->studentId->value(),
            'completed_at' => $new->completedAt,
        ]));
    }

    private function toEntity(LessonProgressModel $model): LessonProgress
    {
        return LessonProgress::reconstitute(
            new LessonProgressId((int) $model->id),
            new LessonId((int) $model->lesson_id),
            new StudentId((int) $model->student_id),
            $model->completed_at,
        );
    }
}
