<?php

namespace App\UseCases;

use App\Contracts\UseCases\CreateLessonUseCaseInterface;
use App\Contracts\Services\SaveVideoServiceInterface;
use App\Contracts\Services\CreateLessonServiceInterface;
use App\Dtos\LessonDto;
use App\Models\Lesson;

class CreateLessonUseCase implements CreateLessonUseCaseInterface
{

    public function __construct(
        private CreateLessonServiceInterface $service,
        private SaveVideoServiceInterface $saveVideoService,
    ) {}

    public function execute(LessonDto $lessonDto): Lesson
    {
        $videoPath = null;
        if ($lessonDto->video) {
            $videoPath = $this->saveVideoService->saveVideo($lessonDto);
        }

        return $this->service->create($lessonDto, $videoPath);
    }
}
