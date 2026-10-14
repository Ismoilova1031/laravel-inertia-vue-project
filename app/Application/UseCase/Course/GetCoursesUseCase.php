<?php

namespace App\Application\UseCase\Course;

final class GetCoursesUseCase
{
    public function __construct(private readonly CourseQueryInterface $query) {}

    /** @return list<CourseListItem> */
    public function execute(): array
    {
        return $this->query->all();
    }
}
