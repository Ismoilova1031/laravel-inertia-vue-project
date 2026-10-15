<?php

namespace App\UseCases;

use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Contracts\UseCases\DeleteLessonUseCaseInterface;
use App\Infrastructure\Eloquent\LessonModel;
use Illuminate\Support\Facades\DB;

class DeleteLessonUseCase implements DeleteLessonUseCaseInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository
    ) {}

    public function execute(LessonModel $lesson): void
    {
        DB::transaction(function () use ($lesson) {
            $this->lessonRepository->delete($lesson);

            $lessons = $this->lessonRepository->getByCourseId($lesson->course_id);

            foreach ($lessons as $index => $lesson) {
                $this->lessonRepository->update($lesson, ['sort_order' => $index + 1]);
            }
        });
    }
}
