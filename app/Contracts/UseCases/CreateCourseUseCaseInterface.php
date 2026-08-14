<?php

namespace App\Contracts\UseCases;

use App\Models\Course;
use App\Dtos\CourseDto;

interface CreateCourseUseCaseInterface
{
    public function execute(CourseDto $dto): Course;
}