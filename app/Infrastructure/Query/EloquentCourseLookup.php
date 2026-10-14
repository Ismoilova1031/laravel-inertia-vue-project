<?php

namespace App\Infrastructure\Query;

use App\Application\Contracts\CourseLookupInterface;
use App\Domain\Course\CourseId;
use App\Enums\CourseStatus;
use App\Models\Course;

final class EloquentCourseLookup implements CourseLookupInterface
{
    public function exists(CourseId $courseId): bool
    {
        return Course::whereKey($courseId->value())->exists();
    }

    public function isPublished(CourseId $courseId): bool
    {
        return Course::whereKey($courseId->value())
            ->where('status', CourseStatus::PUBLISHED->value)
            ->exists();
    }
}