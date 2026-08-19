<?php

namespace App\UseCases;

use App\Contracts\Repositories\LessonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReorderLessonsUseCase
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository,
    ) {
    }

    public function execute(array $lessons): void
    {
        DB::transaction(function () use ($lessons) {
            foreach ($lessons as $lesson) {
                $this->lessonRepository->update(
                    $lesson['id'],
                    [
                        'sort_order' => $lesson['sort_order'],
                    ]
                );
            }
        });
    }
}