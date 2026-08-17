<?php

namespace App\Contracts\Repositories;

use App\Dtos\CourseDto;
use App\Models\Course;
use Illuminate\Support\Collection;

interface CourseRepositoryInterface
{
    public function getCourses(): Collection;

    public function createCourse(CourseDto $dto): Course;

    public function updateCourse(Course $course, CourseDto $dto): Course;

    public function delete(Course $course): void;
}