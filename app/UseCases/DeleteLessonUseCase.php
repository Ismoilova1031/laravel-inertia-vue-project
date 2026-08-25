<?php

namespace App\UseCases;

use App\Contracts\UseCases\DeleteLessonUseCaseInterface;
use App\Models\Lesson;
use App\Contracts\Repositories\LessonRepositoryInterface;

class DeleteLessonUseCase implements DeleteLessonUseCaseInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository
    ) {}

    public function execute(Lesson $lesson): void
    {
        $this->lessonRepository->delete($lesson);
    }
}