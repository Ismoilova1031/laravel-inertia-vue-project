<?php

namespace App\UseCases;

use App\Contracts\UseCases\DeleteLessonUseCaseInterface;
use App\Models\Lesson;
use App\Contracts\Repositories\LessonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DeleteLessonUseCase implements DeleteLessonUseCaseInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository
    ) {}

    public function execute(Lesson $lesson): void
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
