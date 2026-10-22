<?php

namespace App\Application\UseCase\Lesson;

use App\Application\UseCase\Assessment\SaveTaskUseCase;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Lesson\Exception\LessonTaskRequiredException;
use App\Domain\Lesson\LessonContent;
use App\Domain\Lesson\LessonDescription;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\LessonTitle;
use App\Domain\Lesson\LessonType;
use App\Domain\Lesson\NewLesson;
use App\Domain\Lesson\SortOrder;
use App\Domain\Shared\FileStorageInterface;
use Illuminate\Support\Facades\DB;
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

        $new = NewLesson::create(
            $courseId,
            new LessonTitle($command->title),
            new LessonDescription($command->description),
            LessonContent::of($type, $command->content, $videoPath),
            new SortOrder(count($this->lessons->findByCourse($courseId)) + 1)
        );

        try {
            return DB::transaction(function () use ($new, $type, $command) {
                $lesson = $this->lessons->create($new);

                if ($type === LessonType::TASK) {
                    $this->saveTask->execute($lesson->id()->value(), $command->task);
                }

                return $lesson->id();
            });
        } catch (Throwable $e) {
            if ($videoPath !== null) {
                $this->storage->delete($videoPath);
            }

            throw $e;
        }
    }
}
