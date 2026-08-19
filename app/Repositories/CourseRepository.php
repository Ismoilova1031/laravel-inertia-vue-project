<?php

namespace App\Repositories;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Models\Course;
use App\Dtos\CourseDto;
use Illuminate\Support\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function getCourses(): Collection
    {
        return Course::all();
    }

    public function createCourse(CourseDto $dto): Course
    {
        return Course::create($dto->toArray());
    }

    public function updateCourse(Course $course, CourseDto $dto): Course
    {
        $course->update($dto->toArray());
        
        return $course->fresh();
    }

    public function delete(Course $course): void
    {
        $course->delete();
    }

    public function findById(int $id): ?Course
    {
        return Course::find($id);
    }
}