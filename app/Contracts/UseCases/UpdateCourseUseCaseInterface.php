<?php

namespace App\Contracts\UseCases;

use App\Models\Course;
use App\Dtos\CourseDto;

interface UpdateCourseUseCaseInterface
{
    public function execute(Course $course, CourseDto $dto): Course;
}