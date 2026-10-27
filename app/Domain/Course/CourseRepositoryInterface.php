<?php

namespace App\Domain\Course;

use App\Domain\Course\VO\CourseId;
use Illuminate\Support\Collection;

interface CourseRepositoryInterface
{
    public function findById(CourseId $id): ?Course;

    public function create(NewCourse $new): Course;

    public function save(Course $course): void;

    public function delete(CourseId $id): void;

    /** @return Collection<int, Course> */
    public function all(): Collection;

    public function findByIds(array $ids): Collection;
}
