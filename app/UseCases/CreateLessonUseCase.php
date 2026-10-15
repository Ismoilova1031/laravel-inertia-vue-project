<?php

namespace App\UseCases;

use App\Contracts\Services\CreateLessonServiceInterface;
use App\Contracts\Services\SaveVideoServiceInterface;
use App\Contracts\UseCases\CreateLessonUseCaseInterface;
use App\Dtos\LessonDto;
use App\Infrastructure\Eloquent\LessonModel;

class CreateLessonUseCase implements CreateLessonUseCaseInterface
{
    public function __construct(
        private CreateLessonServiceInterface $service,
        private SaveVideoServiceInterface $saveVideoService,
    ) {}

    public function execute(LessonDto $lessonDto): LessonModel
    {
        $videoPath = null;
        if ($lessonDto->video) {
            $videoPath = $this->saveVideoService->saveVideo($lessonDto);
        }

        return $this->service->create($lessonDto, $videoPath);
    }
}
