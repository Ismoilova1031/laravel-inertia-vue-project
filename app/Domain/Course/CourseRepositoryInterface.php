<?php

namespace App\Domain\Course;

interface CourseRepositoryInterface
{
    public function findById(CourseId $id): ?Course;

    public function create(NewCourse $new): Course;

    public function save(Course $course): void;

    public function delete(CourseId $id): void;
}
