<?php

namespace App\Contracts\Repositories;

use App\Dtos\CourseDto;
use App\Models\Course;

interface CourseRepositoryInterface
{
    public function getCourses(): array;

    public function createCourse(CourseDto $dto): Course;
}