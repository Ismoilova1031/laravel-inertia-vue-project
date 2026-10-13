<?php

namespace App\Application\Contracts;

use App\Domain\Course\CourseId;

interface CourseLookupInterface
{
    public function exists(CourseId $courseId): bool;

    public function isPublished(CourseId $courseId): bool;
}