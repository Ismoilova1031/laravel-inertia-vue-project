<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\LessonSequence;
use App\Domain\Shared\FileStorageInterface;
use Illuminate\Support\Facades\DB;

final class DeleteLessonUseCase
{
    public function __construct(
        private readonly LessonRepositoryInterface $lessons,
        private readonly FileStorageInterface $storage,
    ) {}

    public function execute(int $id): void
    {
        $lessonId = new LessonId($id);
        $lesson = $this->lessons->findById($lessonId)
            ?? throw LessonNotFoundException::withId($lessonId);

        DB::transaction(function () use ($lessonId, $lesson) {
            $this->lessons->delete($lessonId);

            $remaining = $this->lessons->findByCourse($lesson->courseId());

            foreach (LessonSequence::renumber($remaining) as $changed) {
                $this->lessons->save($changed);
            }
        });

        if ($lesson->videoPath() !== null) {
            $this->storage->delete($lesson->videoPath());
        }
    }
}
