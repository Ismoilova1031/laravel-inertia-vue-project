<?php

namespace App\Repositories;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Models\Course;
use App\Dtos\CourseDto;

class CourseRepository implements CourseRepositoryInterface
{
    public function getCourses(): array
    {
        return Course::all()->toArray([
            'id',
            'title',
            'description',
            'category',
            'status',
        ]);
    }

    public function createCourse(CourseDto $dto): Course
    {
        return Course::create($dto->toArray());
    }
}