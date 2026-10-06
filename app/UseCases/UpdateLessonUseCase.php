<?php

namespace App\UseCases;

use App\Models\Lesson;
use App\Dtos\LessonDto;
use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Contracts\UseCases\UpdateLessonUseCaseInterface;
use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Contracts\Repositories\StorageRepositoryInterface;
use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Services\UpdateLessonServiceInterface;
use App\Enums\LessonType;
use Illuminate\Support\Str;

class UpdateLessonUseCase implements UpdateLessonUseCaseInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository,
        private TaskRepositoryInterface $taskRepository,
        private StorageRepositoryInterface $storageRepository,
        private CourseRepositoryInterface $courseRepository,
        private UpdateLessonServiceInterface $service
    ) {}

    public function execute(Lesson $lesson, LessonDto $dto): Lesson
    {
        if ($lesson->lesson_type->value != $dto->lesson_type) {
            $this->deleteOthers($lesson, $dto);
        }

        if ($dto->video) {
            if ($lesson->lesson_type === LessonType::VIDEO) {
                $this->storageRepository->deleteFile($lesson->video_url);
            }
            $course = $this->courseRepository->findById($lesson->course_id);

            $videoPath = null;

            if ($dto->video) {
                $videoPath = 'courses/' .
                    $course->category->value . '/' .
                    $course->id . '/' .
                    Str::uuid() . '.' .
                    $dto->video->getClientOriginalExtension();

                $this->storageRepository->storeFile(
                    $videoPath,
                    $dto->video
                );
            }
            return $this->service->update($lesson, $dto, $videoPath);
        } else {
            return $this->service->update($lesson, $dto, $lesson->video_url);
        }
    }

    private function deleteOthers(Lesson $lesson, LessonDto $dto): void
    {
        if ($lesson->lesson_type === LessonType::VIDEO) {

            $this->storageRepository->deleteFile($lesson->video_url);
            $dto->video = null;
            $this->lessonRepository->update($lesson, [
                'video_url' => null,
            ]);
        } else if ($lesson->lesson_type === LessonType::TASK) {
            $this->taskRepository->deleteByLessonId($lesson->id);
        } else {
            $dto->lesson_content = null;
        }
    }
}
