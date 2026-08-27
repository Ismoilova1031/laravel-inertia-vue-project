<?php

namespace App\Services;

use App\Contracts\Services\SaveVideoServiceInterface;
use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Repositories\StorageRepositoryInterface;
use App\Dtos\LessonDto;
use Illuminate\Support\Str;

class SaveVideoService implements SaveVideoServiceInterface
{

    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private StorageRepositoryInterface $storageRepository,
    ) {}

    public function saveVideo(LessonDto $dto): string
    {
        $course = $this->courseRepository->findById($dto->course_id);

        $videoPath = 'courses/' .
            Str::slug($course->category->label()) . '/' .
            Str::slug($course->title) . '/' .
            Str::uuid() . '.' .
            $dto->video->getClientOriginalExtension();

        $this->storageRepository->storeFile(
            $videoPath,
            $dto->video
        );

        return $videoPath;
    }
}
