<?php

namespace App\Domain\Course;

interface CourseRepositoryInterface
{
    public function findById(CourseId $id): ?Course;

    public function save(Course $course): Course;

    public function delete(CourseId $id): void;
}
