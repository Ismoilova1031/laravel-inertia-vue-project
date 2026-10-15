<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Course\CourseId;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\SortOrder;
use Illuminate\Support\Facades\DB;

final class ReorderLessonsUseCase
{
    public function __construct(private readonly LessonRepositoryInterface $lessons) {}

    public function execute(ReorderLessonsCommand $command): void
    {
        $byId = [];
        foreach($this->lessons->findByCourse(new CourseId($command->courseId)) as $lesson) {
            $byId[$lesson->id()->value()] = $lesson;
        }

        DB::transaction(function () use ($command, $byId){
            foreach($command->positions as $lessonId => $sortOrder){
                $lesson = $byId[$lessonId] ?? throw LessonNotFoundException::withId(new LessonId($lessonId));

                $lesson->moveTo(new SortOrder($sortOrder));
                $this->lessons->save($lesson);
            }
        });
    }
}