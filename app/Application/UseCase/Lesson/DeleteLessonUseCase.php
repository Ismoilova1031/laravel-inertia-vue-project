<?php

namespace App\Application\UseCase\Lesson;

use App\Application\Contracts\FileStorageInterface;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\SortOrder;
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

        DB::transaction(function () use ($lessonId, $lesson){
            $this->lessons->delete($lessonId);

            foreach($this->lessons->findByCourse($lesson->courseId()) as $index => $other) {
                $position = new SortOrder($index + 1);

                if(!$other->sortOrder()->equals($position)) {
                    $other->moveTo($position);
                    $this->lessons->save($other);
                }
            }
        });

        if($lesson->videoPath() !== null){
            $this->storage->delete($lesson->videoPath());
        }
    }
}