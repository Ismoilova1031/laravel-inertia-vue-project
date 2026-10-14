<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\CourseId;
use App\Domain\Course\Exception\CourseNotFoundException;

final class GetCourseUseCase
{
    public function __construct(private readonly CourseQueryInterface $query) {}

    public function execute(int $id): CourseDetail
    {
        return $this->query->detail($id)
            ?? throw CourseNotFoundException::withId(new CourseId($id));
    }
}
