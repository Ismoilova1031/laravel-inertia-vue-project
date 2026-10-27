<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Course\VO\CourseId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\LessonSequence;
use Illuminate\Support\Facades\DB;

final class ReorderLessonsUseCase
{
    public function __construct(private readonly LessonRepositoryInterface $lessons) {}

    public function execute(ReorderLessonsCommand $command): void
    {
        $lessons = $this->lessons->findByCourse(new CourseId($command->courseId));

        $changed = LessonSequence::arrange($lessons, $command->orderedIds);

        DB::transaction(function () use ($changed) {
            foreach ($changed as $lesson) {
                $this->lessons->save($lesson);
            }
        });
    }
}
