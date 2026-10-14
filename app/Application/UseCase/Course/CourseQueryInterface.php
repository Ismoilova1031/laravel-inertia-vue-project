<?php

namespace App\Application\UseCase\Course;

interface CourseQueryInterface
{
    /** @return list<CourseListItem> */
    public function all(): array;

    public function detail(int $id): ?CourseDetail;
}
