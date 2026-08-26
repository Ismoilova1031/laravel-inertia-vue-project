<?php

namespace App\UseCases;

use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Contracts\UseCases\CreateLessonUseCaseInterface;
use App\Contracts\Repositories\StorageRepositoryInterface;
use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Dtos\LessonDto;
use App\Models\Lesson;
use Illuminate\Support\Str;
class CreateLessonUseCase implements CreateLessonUseCaseInterface
{
    private LessonRepositoryInterface $lessonRepository;
    private StorageRepositoryInterface $storageRepository;
    private CourseRepositoryInterface $courseRepository;
    
    public function __construct(LessonRepositoryInterface $lessonRepository, StorageRepositoryInterface $storageRepository, CourseRepositoryInterface $courseRepository)
    {
        $this->lessonRepository = $lessonRepository;
        $this->storageRepository = $storageRepository;
        $this->courseRepository = $courseRepository;
    }

    public function execute(LessonDto $lessonDto): Lesson
    {
        $course = $this->courseRepository->findById($lessonDto->course_id);

        $videoPath = null;

        if ($lessonDto->video) {
            $videoPath = 'courses/' .
                Str::slug($course->category->label()) . '/' .
                Str::slug($course->title) . '/' .
                Str::uuid() . '.' .
                $lessonDto->video->getClientOriginalExtension();

            $this->storageRepository->storeFile(
                $videoPath,
                $lessonDto->video
            );
        }

        return $this->lessonRepository->create($lessonDto->toArray($videoPath));
    }
}