<?php

namespace App\Application\UseCase\Lesson;   

use App\Application\Contracts\FileStorageInterface;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\Course\Exception\CourseNotFoundException;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\Exception\LessonTaskRequiredException;
use App\Domain\Lesson\LessonDescription;
use App\Domain\Lesson\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\LessonTitle;
use App\Domain\Lesson\LessonType;
use App\Domain\Lesson\SortOrder;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateLessonUseCase
{
    public function __construct(
        private readonly LessonRepositoryInterface $lessons,
        private readonly CourseRepositoryInterface $courses,
        private readonly FileStorageInterface $storage,
        private readonly LessonTaskGatewayInterface $tasks,
    ) {}

    public function execute(UpdateLessonCommand $command): void
    {
        $id = new LessonId($command->id);
        $lesson = $this->lessons->findById($id)
            ?? throw LessonNotFoundException::withId($id);

        $type = LessonType::from($command->type);

        if($type === LessonType::TASK && $command->task === null){
            throw LessonTaskRequiredException::create();
        }

        $course = $this->courses->findById($lesson->courseId())
            ?? throw CourseNotFoundException::withId($lesson->courseId());

        $previousType = $lesson->type();
        $previousVideo = $lesson->videoPath();

        $newVideoPath = ($type === LessonType::VIDEO && $command->video !== null)
            ? $this->storage->store(
                "courses/{$course->category()->value}/{$course->id()->value()}",
                $command->video,
            )
            : null;

        $lesson->revise(
            new LessonTitle($command->title),
            new LessonDescription($command->description),
            $type,
            $command->content,
            $newVideoPath,
        );
        $lesson->moveTo(new SortOrder($command->sortOrder));

        try {
            DB::transaction(function () use ($lesson, $id, $type, $previousType, $command){
                $this->lessons->save($lesson);

                if($type === LessonType::TASK){
                    $this->tasks->replaceFor($id->value(), $command->task);
                }elseif($previousType === LessonType::TASK){
                    $this->tasks->deleteFor($id->value());
                }
            } );
        } catch (Throwable $e){
            if($newVideoPath !== null){
                $this->storage->delete($newVideoPath);
            }

            throw $e;
        }

        if($previousVideo !== null && $previousVideo !== $lesson->videoPath()){
            $this->storage->delete($previousVideo);
        }
    }
}