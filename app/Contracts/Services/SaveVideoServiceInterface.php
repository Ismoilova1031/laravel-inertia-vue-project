<?php

namespace App\Contracts\Services;

use App\Dtos\LessonDto;

interface SaveVideoServiceInterface
{
    public function saveVideo(LessonDto $dto): string;
}