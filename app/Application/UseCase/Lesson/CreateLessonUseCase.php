<?php

namespace App\Application\UseCase\Lesson;

use App\Application\Contracts\FileStorageInterface;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Lesson\Exception\LessonTaskRequiredException;
use App\Domain\Lesson\Lesson;
use App\Domain\Lesson\LessonDescription;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\LessonTitle;
use App\Domain\Lesson\LessonType;
use App\Domain\Lesson\SortOrder;
use Illuminate\Support\Facades\DB;
use App\Application\UseCase\Assessment\SaveTaskUseCase;
use Throwable;

final class CreateLessonUseCase
{
    public function __construct(
        private readonly LessonRepositoryInterface $lessons,
        private readonly CourseRepositoryInterface $courses,
        private readonly FileStorageInterface $storage,
        private readonly SaveTaskUseCase $saveTask,
    ) {}

    public function execute(CreateLessonCommand $command): LessonId
    {
        $courseId = new CourseId($command->courseId);
        $course = $this->courses->findById($courseId)
            ?? throw CourseNotFoundException::withId($courseId);

        $type = LessonType::from($command->type);

        if ($type === LessonType::TASK && $command->task === null) {
            throw LessonTaskRequiredException::create();
        }

        $videoPath = ($type === LessonType::VIDEO && $command->video !== null)
            ? $this->storage->store("courses/{$course->category()->value}/{$courseId->value()}", $command->video)
            : null;

        $lesson = Lesson::create(
            $courseId,
            new LessonTitle($command->title),
            new LessonDescription($command->description),
            $type,
            new SortOrder(count($this->lessons->findByCourse($courseId)) + 1),
            $command->content,
            $videoPath,
        );

        try {
            return DB::transaction(function () use ($lesson, $type, $command) {
                $saved = $this->lessons->save($lesson);

                if ($type === LessonType::TASK) {
                    $this->saveTask->execute($saved->id()->value(), $command->task);;
                }

                return $saved->id();
            });
        } catch (Throwable $e) {
            if ($videoPath !== null) {
                $this->storage->delete($videoPath);
            }

            throw $e;
        }
    }
}
